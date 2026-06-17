<?php
/** Toggle a bookmark for a topic. */
require_once __DIR__ . '/../includes/auth.php';

if (!is_logged_in()) {
    json_response(['ok' => false, 'error' => 'auth'], 401);
}
$input = json_decode(file_get_contents('php://input'), true) ?: [];
if (!csrf_check($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    json_response(['ok' => false, 'error' => 'csrf'], 419);
}

$topicId = (int) ($input['topic_id'] ?? 0);
$on      = !empty($input['on']);
$userId  = current_user_id();

if ($on) {
    db()->prepare('INSERT IGNORE INTO bookmarks (user_id, topic_id) VALUES (?, ?)')
        ->execute([$userId, $topicId]);
} else {
    db()->prepare('DELETE FROM bookmarks WHERE user_id = ? AND topic_id = ?')
        ->execute([$userId, $topicId]);
}

json_response(['ok' => true, 'on' => $on]);
