<?php
/** Save a user's note for a topic. */
require_once __DIR__ . '/../includes/auth.php';

if (!is_logged_in()) {
    json_response(['ok' => false, 'error' => 'auth'], 401);
}
$input = json_decode(file_get_contents('php://input'), true) ?: [];
if (!csrf_check($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    json_response(['ok' => false, 'error' => 'csrf'], 419);
}

$topicId = (int) ($input['topic_id'] ?? 0);
$note    = (string) ($input['note'] ?? '');
$userId  = current_user_id();

db()->prepare(
    'INSERT INTO user_notes (user_id, topic_id, note_text, updated_at)
     VALUES (?,?,?,NOW())
     ON DUPLICATE KEY UPDATE note_text = VALUES(note_text), updated_at = NOW()'
)->execute([$userId, $topicId, $note]);

json_response(['ok' => true]);
