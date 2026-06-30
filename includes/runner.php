<?php
/**
 * Code runner for the built-in compiler.
 *
 * - PHP & Python run locally (offline, private) via the configured interpreters.
 * - C++ & Java run locally when g++/javac exist, otherwise via the Piston API.
 *
 * SECURITY: this executes user-supplied code on the server. It is intended for
 * a local, single-user learning install. Do NOT expose this endpoint on the
 * public internet without proper sandboxing (containers, seccomp, quotas).
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

/** Normalize program output for comparison (line-trim + trailing newline trim). */
function normalize_output(string $s): string
{
    $s = str_replace(["\r\n", "\r"], "\n", $s);
    $lines = array_map('rtrim', explode("\n", $s));
    return rtrim(implode("\n", $lines), "\n");
}

/** Resolve a usable PHP CLI binary (XAMPP path → PHP_BINARY when on CLI → "php"). */
function php_cli_bin(): ?string
{
    static $bin = false;
    if ($bin !== false) {
        return $bin;
    }
    if (PHP_CLI && @is_file(PHP_CLI)) {
        return $bin = PHP_CLI;
    }
    if (PHP_SAPI === 'cli' && defined('PHP_BINARY') && PHP_BINARY && @is_file(PHP_BINARY)) {
        return $bin = PHP_BINARY;
    }
    return $bin = (tool_available('php', '--version') ? 'php' : null);
}

/** Resolve a usable Python binary (PYTHON_BIN → python3). */
function python_bin(): ?string
{
    static $bin = false;
    if ($bin !== false) {
        return $bin;
    }
    if (tool_available(PYTHON_BIN, '--version')) {
        return $bin = PYTHON_BIN;
    }
    if (tool_available('python3', '--version')) {
        return $bin = 'python3';
    }
    return $bin = null;
}

/** Is an external tool runnable? Cached per request. */
function tool_available(string $bin, string $versionFlag = '--version'): bool
{
    static $cache = [];
    if (isset($cache[$bin])) {
        return $cache[$bin];
    }
    $res = exec_process([$bin, $versionFlag], '', 4000);
    return $cache[$bin] = ($res['started'] && !$res['timed_out']);
}

/**
 * Run a command with stdin and a wall-clock timeout. Returns stdout/stderr/exit.
 *
 * @return array{started:bool,exit:int,stdout:string,stderr:string,timed_out:bool}
 */
function exec_process(array $cmd, string $stdin, int $timeoutMs, ?string $cwd = null): array
{
    $desc = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
    $opts = ['bypass_shell' => true];
    $proc = @proc_open($cmd, $desc, $pipes, $cwd ?: sys_get_temp_dir(), null, $opts);
    if (!is_resource($proc)) {
        return ['started' => false, 'exit' => -1, 'stdout' => '', 'stderr' => 'Failed to start process', 'timed_out' => false];
    }

    if ($stdin !== '') {
        fwrite($pipes[0], $stdin);
    }
    fclose($pipes[0]);
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);

    $pid = (int) (proc_get_status($proc)['pid'] ?? 0);
    $isWin = DIRECTORY_SEPARATOR === '\\';
    $kill = function () use ($proc, $pid, $isWin) {
        if ($isWin && $pid > 0) {
            // Forcibly kill the process tree (proc_terminate alone can leak
            // CPU-bound children on Windows).
            @exec('taskkill /F /T /PID ' . $pid . ' 2>NUL');
        }
        @proc_terminate($proc);
    };

    $stdout = $stderr = '';
    $start = microtime(true);
    $timedOut = false;

    while (true) {
        $status = proc_get_status($proc);
        $stdout .= stream_get_contents($pipes[1]);
        $stderr .= stream_get_contents($pipes[2]);

        if (!$status['running']) {
            break;
        }
        if ((microtime(true) - $start) * 1000 > $timeoutMs) {
            $kill();
            $timedOut = true;
            break;
        }
        if (strlen($stdout) + strlen($stderr) > EXEC_OUTPUT_LIMIT) {
            $kill();
            break;
        }
        usleep(8000);
    }

    $stdout .= stream_get_contents($pipes[1]);
    $stderr .= stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exit = proc_close($proc);

    return [
        'started'   => true,
        'exit'      => $timedOut ? -1 : $exit,
        'stdout'    => substr($stdout, 0, EXEC_OUTPUT_LIMIT),
        'stderr'    => substr($stderr, 0, EXEC_OUTPUT_LIMIT),
        'timed_out' => $timedOut,
    ];
}

/** Create and return a unique temp working directory. */
function exec_tmpdir(): string
{
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'dsarun_' . bin2hex(random_bytes(5));
    @mkdir($dir, 0700, true);
    return $dir;
}

function rrmdir(string $dir): void
{
    foreach (glob($dir . '/*') ?: [] as $f) {
        is_dir($f) ? rrmdir($f) : @unlink($f);
    }
    @rmdir($dir);
}

/**
 * Compile (if needed) and run source for a language with given stdin.
 *
 * @return array{ok:bool,stdout:string,stderr:string,compile_error:string,timed_out:bool,backend:string}
 */
function run_code(string $lang, string $source, string $stdin = ''): array
{
    $limit = EXEC_TIME_LIMIT_MS;
    $result = fn(bool $ok, string $out = '', string $err = '', string $ce = '', bool $to = false, string $be = 'local')
        => ['ok' => $ok, 'stdout' => $out, 'stderr' => $err, 'compile_error' => $ce, 'timed_out' => $to, 'backend' => $be];

    // ---- Local interpreters -------------------------------------------------
    if ($lang === 'php' && php_cli_bin()) {
        $dir = exec_tmpdir();
        file_put_contents("$dir/main.php", $source);
        $r = exec_process([php_cli_bin(), '-d', 'display_errors=stderr', "$dir/main.php"], $stdin, $limit, $dir);
        rrmdir($dir);
        return $result(!$r['timed_out'] && $r['exit'] === 0, $r['stdout'], $r['stderr'], '', $r['timed_out']);
    }

    if ($lang === 'python' && python_bin()) {
        $dir = exec_tmpdir();
        file_put_contents("$dir/main.py", $source);
        $r = exec_process([python_bin(), "$dir/main.py"], $stdin, $limit, $dir);
        rrmdir($dir);
        return $result(!$r['timed_out'] && $r['exit'] === 0, $r['stdout'], $r['stderr'], '', $r['timed_out']);
    }

    if ($lang === 'cpp' && tool_available(GPP_BIN, '--version')) {
        $dir = exec_tmpdir();
        file_put_contents("$dir/main.cpp", $source);
        $exe = "$dir/main.exe";
        $c = exec_process([GPP_BIN, '-O2', '-std=c++17', "$dir/main.cpp", '-o', $exe], '', 12000, $dir);
        if ($c['exit'] !== 0) {
            rrmdir($dir);
            return $result(false, '', '', $c['stderr'] ?: 'Compilation failed');
        }
        $r = exec_process([$exe], $stdin, $limit, $dir);
        rrmdir($dir);
        return $result(!$r['timed_out'] && $r['exit'] === 0, $r['stdout'], $r['stderr'], '', $r['timed_out']);
    }

    if ($lang === 'java' && tool_available(JAVAC_BIN, '-version')) {
        $dir = exec_tmpdir();
        file_put_contents("$dir/Main.java", $source);
        $c = exec_process([JAVAC_BIN, "$dir/Main.java"], '', 15000, $dir);
        if ($c['exit'] !== 0) {
            rrmdir($dir);
            return $result(false, '', '', $c['stderr'] ?: 'Compilation failed');
        }
        $r = exec_process([JAVA_BIN, '-cp', $dir, 'Main'], $stdin, $limit, $dir);
        rrmdir($dir);
        return $result(!$r['timed_out'] && $r['exit'] === 0, $r['stdout'], $r['stderr'], '', $r['timed_out']);
    }

    // ---- Remote fallback (Piston) ------------------------------------------
    if (EXEC_ENABLE_REMOTE) {
        return piston_run($lang, $source, $stdin);
    }

    return $result(false, '', "No runtime available for {$lang} on this server.", '', false, 'none');
}

/** Execute via the Piston public API. */
function piston_run(string $lang, string $source, string $stdin): array
{
    $map = [
        'php'    => ['language' => 'php',     'file' => 'main.php'],
        'python' => ['language' => 'python',  'file' => 'main.py'],
        'cpp'    => ['language' => 'c++',     'file' => 'main.cpp'],
        'java'   => ['language' => 'java',    'file' => 'Main.java'],
    ];
    if (!isset($map[$lang])) {
        return ['ok' => false, 'stdout' => '', 'stderr' => 'Unsupported language', 'compile_error' => '', 'timed_out' => false, 'backend' => 'remote'];
    }

    $version = piston_version($map[$lang]['language']);
    if ($version === null) {
        return ['ok' => false, 'stdout' => '', 'stderr' => 'Code execution service is unavailable (no internet?).', 'compile_error' => '', 'timed_out' => false, 'backend' => 'remote'];
    }

    $payload = json_encode([
        'language' => $map[$lang]['language'],
        'version'  => $version,
        'files'    => [['name' => $map[$lang]['file'], 'content' => $source]],
        'stdin'    => $stdin,
        'run_timeout' => EXEC_TIME_LIMIT_MS,
    ]);

    $resp = http_post_json(PISTON_URL . '/execute', $payload, 15);
    if ($resp === null) {
        return ['ok' => false, 'stdout' => '', 'stderr' => 'Could not reach the execution service.', 'compile_error' => '', 'timed_out' => false, 'backend' => 'remote'];
    }
    $data = json_decode($resp, true) ?: [];
    $compile = $data['compile'] ?? null;
    $run = $data['run'] ?? [];
    $ce = ($compile && (int) ($compile['code'] ?? 0) !== 0) ? (string) ($compile['stderr'] ?? $compile['output'] ?? '') : '';

    return [
        'ok'            => $ce === '' && (int) ($run['code'] ?? 1) === 0,
        'stdout'        => (string) ($run['stdout'] ?? ''),
        'stderr'        => (string) ($run['stderr'] ?? ''),
        'compile_error' => $ce,
        'timed_out'     => false,
        'backend'       => 'remote',
    ];
}

/** Resolve the newest Piston version for a language (cached to a temp file for a day). */
function piston_version(string $language): ?string
{
    static $runtimes = null;
    if ($runtimes === null) {
        $cacheFile = sys_get_temp_dir() . '/dsa_piston_runtimes.json';
        if (is_file($cacheFile) && (time() - filemtime($cacheFile) < 86400)) {
            $runtimes = json_decode((string) file_get_contents($cacheFile), true) ?: [];
        } else {
            $raw = http_get(PISTON_URL . '/runtimes', 12);
            $runtimes = $raw ? (json_decode($raw, true) ?: []) : [];
            if ($runtimes) {
                @file_put_contents($cacheFile, json_encode($runtimes));
            }
        }
    }
    $best = null;
    foreach ($runtimes as $rt) {
        $names = array_merge([$rt['language'] ?? ''], $rt['aliases'] ?? []);
        if (in_array($language, $names, true)) {
            $best = $rt['version'] ?? $best; // list is generally newest-first
            break;
        }
    }
    return $best;
}

/** Minimal HTTP helpers via the stream wrapper (no curl dependency). */
function http_post_json(string $url, string $body, int $timeout): ?string
{
    $ctx = stream_context_create([
        'http' => ['method' => 'POST', 'header' => "Content-Type: application/json\r\n", 'content' => $body, 'timeout' => $timeout, 'ignore_errors' => true],
        'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
    ]);
    $res = @file_get_contents($url, false, $ctx);
    return $res === false ? null : $res;
}

function http_get(string $url, int $timeout): ?string
{
    $ctx = stream_context_create([
        'http' => ['method' => 'GET', 'timeout' => $timeout, 'ignore_errors' => true],
        'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
    ]);
    $res = @file_get_contents($url, false, $ctx);
    return $res === false ? null : $res;
}
