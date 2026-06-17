<?php
/** Mark a topic complete / incomplete. Returns updated chapter & overall %. */
require_once __DIR__ . '/../includes/auth.php';

if (!is_logged_in()) {
    json_response(['ok' => false, 'error' => 'auth'], 401);
}

$input = json_decode(file_get_contents('php://input'), true) ?: [];
if (!csrf_check($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    json_response(['ok' => false, 'error' => 'csrf'], 419);
}

$topicId   = (int) ($input['topic_id'] ?? 0);
$completed = !empty($input['completed']);
$userId    = current_user_id();

$stmt = db()->prepare('SELECT chapter_id FROM topics WHERE id = ?');
$stmt->execute([$topicId]);
$chapterId = $stmt->fetchColumn();
if (!$chapterId) {
    json_response(['ok' => false, 'error' => 'not_found'], 404);
}

if ($completed) {
    db()->prepare(
        'INSERT INTO user_progress (user_id, topic_id, status, completed_at)
         VALUES (?,?,"completed",NOW())
         ON DUPLICATE KEY UPDATE status="completed", completed_at=NOW()'
    )->execute([$userId, $topicId]);
    record_activity($userId);
    evaluate_achievements($userId);
} else {
    db()->prepare('DELETE FROM user_progress WHERE user_id = ? AND topic_id = ?')
        ->execute([$userId, $topicId]);
}

$chapter = chapter_progress($userId, (int) $chapterId);
$overall = overall_progress($userId);

json_response([
    'ok'              => true,
    'completed'       => $completed,
    'chapter_percent' => $chapter['percent'],
    'overall_percent' => $overall['percent'],
]);
