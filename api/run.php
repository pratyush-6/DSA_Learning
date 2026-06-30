<?php
/** Run user code once against optional custom stdin; return program output. */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/runner.php';

if (!is_logged_in()) {
    json_response(['ok' => false, 'error' => 'auth'], 401);
}
$input = json_decode(file_get_contents('php://input'), true) ?: [];
if (!csrf_check($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    json_response(['ok' => false, 'error' => 'csrf'], 419);
}

$lang   = (string) ($input['language'] ?? '');
$source = (string) ($input['source'] ?? '');
$stdin  = (string) ($input['stdin'] ?? '');

if (!isset(LANGUAGES[$lang])) {
    json_response(['ok' => false, 'error' => 'bad_language'], 400);
}
if (strlen($source) > 60000) {
    json_response(['ok' => false, 'error' => 'source_too_large'], 413);
}

$r = run_code($lang, $source, $stdin);
json_response([
    'ok'            => $r['ok'],
    'stdout'        => $r['stdout'],
    'stderr'        => $r['stderr'],
    'compile_error' => $r['compile_error'],
    'timed_out'     => $r['timed_out'],
    'backend'       => $r['backend'],
]);
