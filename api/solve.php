<?php
/** Mark a practice problem solved / unsolved for the current user. */
require_once __DIR__ . '/../includes/auth.php';

if (!is_logged_in()) {
    json_response(['ok' => false, 'error' => 'auth'], 401);
}
$input = json_decode(file_get_contents('php://input'), true) ?: [];
if (!csrf_check($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    json_response(['ok' => false, 'error' => 'csrf'], 419);
}

$problemId = (int) ($input['problem_id'] ?? 0);
$solved    = !empty($input['solved']);
$userId    = current_user_id();

$stmt = db()->prepare('SELECT 1 FROM practice_problems WHERE id = ?');
$stmt->execute([$problemId]);
if (!$stmt->fetchColumn()) {
    json_response(['ok' => false, 'error' => 'not_found'], 404);
}

if ($solved) {
    // INSERT IGNORE prevents duplicate solved records (PK user_id+problem_id).
    db()->prepare(
        'INSERT IGNORE INTO user_problem_solved (user_id, problem_id, solved_at) VALUES (?,?,NOW())'
    )->execute([$userId, $problemId]);
    record_activity($userId);
} else {
    db()->prepare('DELETE FROM user_problem_solved WHERE user_id = ? AND problem_id = ?')
        ->execute([$userId, $problemId]);
}

$total = (int) db()->query("SELECT COUNT(*) FROM user_problem_solved WHERE user_id = {$userId}")->fetchColumn();

json_response(['ok' => true, 'solved' => $solved, 'total_solved' => $total]);
