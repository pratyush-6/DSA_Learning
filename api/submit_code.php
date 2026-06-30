<?php
/**
 * Submit code against a problem's predefined test cases.
 * The problem is marked SOLVED only when ALL test cases pass.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/runner.php';
require_once __DIR__ . '/../includes/ratelimit.php';

if (!is_logged_in()) {
    json_response(['ok' => false, 'error' => 'auth'], 401);
}
$input = json_decode(file_get_contents('php://input'), true) ?: [];
if (!csrf_check($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    json_response(['ok' => false, 'error' => 'csrf'], 419);
}

// Throttle submissions: 20 / minute per user.
$rl = rate_limit('submit:' . current_user_id(), 20, 60);
if (!$rl['allowed']) {
    json_response(['ok' => false, 'error' => 'rate_limited', 'retry_after' => $rl['retry_after']], 429);
}

$problemId = (int) ($input['problem_id'] ?? 0);
$lang      = (string) ($input['language'] ?? '');
$source    = (string) ($input['source'] ?? '');
$userId    = current_user_id();

if (!isset(LANGUAGES[$lang])) {
    json_response(['ok' => false, 'error' => 'bad_language'], 400);
}
if (strlen($source) > 60000) {
    json_response(['ok' => false, 'error' => 'source_too_large'], 413);
}

$stmt = db()->prepare('SELECT id FROM practice_problems WHERE id = ?');
$stmt->execute([$problemId]);
if (!$stmt->fetchColumn()) {
    json_response(['ok' => false, 'error' => 'not_found'], 404);
}

$tcStmt = db()->prepare('SELECT * FROM problem_testcases WHERE problem_id = ? ORDER BY sort_order, id');
$tcStmt->execute([$problemId]);
$tests = $tcStmt->fetchAll();
if (!$tests) {
    json_response(['ok' => false, 'error' => 'no_testcases'], 400);
}

$results = [];
$passed = 0;
$backend = 'local';
foreach ($tests as $i => $tc) {
    $run = run_code($lang, $source, (string) $tc['stdin']);
    $backend = $run['backend'];

    // A hard failure (compile error / crash / timeout) fails the case.
    $got      = normalize_output($run['stdout']);
    $expected = normalize_output((string) $tc['expected_output']);
    $ok = $run['ok'] && $run['compile_error'] === '' && $got === $expected;
    if ($ok) {
        $passed++;
    }

    $isSample = (int) $tc['is_sample'] === 1;
    $row = [
        'index'   => $i + 1,
        'sample'  => $isSample,
        'passed'  => $ok,
        'timed_out' => $run['timed_out'],
    ];
    // Reveal details only for sample cases (keep hidden tests hidden).
    if ($isSample) {
        $row['input']    = (string) $tc['stdin'];
        $row['expected'] = (string) $tc['expected_output'];
        $row['got']      = $run['stdout'];
    }
    if ($run['compile_error'] !== '') {
        $row['compile_error'] = $run['compile_error'];
    } elseif (!$ok && $run['stderr'] !== '') {
        $row['stderr'] = $run['stderr'];
    }
    $results[] = $row;

    // Stop early on compile error (same for every case).
    if ($run['compile_error'] !== '') {
        break;
    }
}

$total = count($tests);
$allPassed = $passed === $total;

// Persist the submission (latest per user/problem/language).
db()->prepare(
    'INSERT INTO user_submissions (user_id, problem_id, language, code, passed, total, updated_at)
     VALUES (?,?,?,?,?,?,NOW())
     ON DUPLICATE KEY UPDATE code=VALUES(code), passed=VALUES(passed), total=VALUES(total), updated_at=NOW()'
)->execute([$userId, $problemId, $lang, $source, $passed, $total]);

$solved = false;
if ($allPassed) {
    // Mark solved ONLY when all test cases pass (idempotent).
    db()->prepare('INSERT IGNORE INTO user_problem_solved (user_id, problem_id, solved_at) VALUES (?,?,NOW())')
        ->execute([$userId, $problemId]);
    record_activity($userId);
    $solved = true;
}

json_response([
    'ok'         => true,
    'passed'     => $passed,
    'total'      => $total,
    'all_passed' => $allPassed,
    'solved'     => $solved,
    'results'    => $results,
    'backend'    => $backend,
]);
