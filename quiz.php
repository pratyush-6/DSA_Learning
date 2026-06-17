<?php
require_once __DIR__ . '/includes/auth.php';
require_login();

$quizId = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare(
    'SELECT q.*, c.title AS chapter_title, c.slug AS chapter_slug
     FROM quizzes q JOIN chapters c ON c.id = q.chapter_id WHERE q.id = ?'
);
$stmt->execute([$quizId]);
$quiz = $stmt->fetch();
if (!$quiz) {
    http_response_code(404);
    require __DIR__ . '/partials/header.php';
    echo '<div class="alert alert-danger">Quiz not found.</div>';
    require __DIR__ . '/partials/footer.php';
    exit;
}

$qStmt = db()->prepare('SELECT * FROM quiz_questions WHERE quiz_id = ? ORDER BY sort_order, id');
$qStmt->execute([$quizId]);
$questions = $qStmt->fetchAll();

$optStmt = db()->prepare(
    'SELECT o.* FROM quiz_options o JOIN quiz_questions q ON q.id = o.question_id
     WHERE q.quiz_id = ? ORDER BY o.question_id, o.sort_order, o.id'
);
$optStmt->execute([$quizId]);
$optionsByQ = [];
foreach ($optStmt->fetchAll() as $o) {
    $optionsByQ[(int) $o['question_id']][] = $o;
}

$pageTitle = $quiz['title'];
require __DIR__ . '/partials/header.php';
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('chapter.php?slug=' . urlencode($quiz['chapter_slug'])) ?>"><?= e($quiz['chapter_title']) ?></a></li>
    <li class="breadcrumb-item active">Quiz</li>
  </ol>
</nav>

<h2 class="mb-3"><i class="bi bi-patch-question"></i> <?= e($quiz['title']) ?></h2>
<div id="quiz-result"></div>

<form id="quiz-form" data-quiz-id="<?= $quizId ?>">
  <?php foreach ($questions as $i => $q):
      $qid = (int) $q['id']; ?>
  <div class="card mb-3 shadow-sm" data-question-id="<?= $qid ?>">
    <div class="card-body">
      <p class="fw-semibold"><?= $i + 1 ?>. <?= e($q['question']) ?></p>
      <?php foreach ($optionsByQ[$qid] ?? [] as $opt): ?>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="q_<?= $qid ?>" value="<?= (int) $opt['id'] ?>" id="opt<?= (int) $opt['id'] ?>">
          <label class="form-check-label" for="opt<?= (int) $opt['id'] ?>"><?= e($opt['option_text']) ?></label>
        </div>
      <?php endforeach; ?>
      <?php if ($q['explanation_md']): ?>
        <div class="quiz-explanation alert alert-info mt-2 d-none">
          <strong>Explanation:</strong> <?= render_markdown($q['explanation_md']) ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>

  <?php if ($questions): ?>
    <button class="btn btn-info btn-lg" type="submit"><i class="bi bi-check2-circle"></i> Submit Quiz</button>
  <?php else: ?>
    <div class="alert alert-secondary">No questions in this quiz yet.</div>
  <?php endif; ?>
</form>
<?php require __DIR__ . '/partials/footer.php'; ?>
