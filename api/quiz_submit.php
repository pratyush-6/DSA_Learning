<?php
/** Score a quiz submission, store the attempt, return per-question results. */
require_once __DIR__ . '/../includes/auth.php';

if (!is_logged_in()) {
    json_response(['ok' => false, 'error' => 'auth'], 401);
}
$input = json_decode(file_get_contents('php://input'), true) ?: [];
if (!csrf_check($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    json_response(['ok' => false, 'error' => 'csrf'], 419);
}

$quizId  = (int) ($input['quiz_id'] ?? 0);
$answers = is_array($input['answers'] ?? null) ? $input['answers'] : [];
$userId  = current_user_id();

// Load questions and their correct option for this quiz.
$stmt = db()->prepare(
    'SELECT qq.id AS question_id,
            (SELECT id FROM quiz_options WHERE question_id = qq.id AND is_correct = 1 LIMIT 1) AS correct_option_id
     FROM quiz_questions qq WHERE qq.quiz_id = ? ORDER BY qq.sort_order, qq.id'
);
$stmt->execute([$quizId]);
$questions = $stmt->fetchAll();
if (!$questions) {
    json_response(['ok' => false, 'error' => 'not_found'], 404);
}

$score   = 0;
$details = [];
foreach ($questions as $q) {
    $qid     = (int) $q['question_id'];
    $correct = (int) $q['correct_option_id'];
    $chosen  = isset($answers[$qid]) ? (int) $answers[$qid] : 0;
    $isRight = $chosen === $correct && $correct > 0;
    if ($isRight) {
        $score++;
    }
    $details[] = [
        'question_id'       => $qid,
        'correct_option_id' => $correct,
        'chosen_option_id'  => $chosen,
        'correct'           => $isRight,
    ];
}
$total = count($questions);

db()->prepare('INSERT INTO user_quiz_attempts (user_id, quiz_id, score, total) VALUES (?,?,?,?)')
    ->execute([$userId, $quizId, $score, $total]);
record_activity($userId);
award_achievement($userId, 'first_quiz');

json_response(['ok' => true, 'score' => $score, 'total' => $total, 'details' => $details]);
