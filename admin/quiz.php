<?php
require_once __DIR__ . '/_layout.php';

$chapterId = (int) ($_GET['chapter_id'] ?? ($_POST['chapter_id'] ?? 0));
$stmt = db()->prepare('SELECT * FROM chapters WHERE id = ?');
$stmt->execute([$chapterId]);
$chapter = $stmt->fetch();
if (!$chapter) { redirect('admin/chapters.php'); }

// Ensure a quiz exists for this chapter.
$q = db()->prepare('SELECT * FROM quizzes WHERE chapter_id = ? LIMIT 1');
$q->execute([$chapterId]);
$quiz = $q->fetch();
if (!$quiz) {
    db()->prepare('INSERT INTO quizzes (chapter_id, title) VALUES (?,?)')->execute([$chapterId, $chapter['title'] . ' Quiz']);
    $quizId = (int) db()->lastInsertId();
} else {
    $quizId = (int) $quiz['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        db()->prepare('DELETE FROM quiz_questions WHERE id = ?')->execute([(int) $_POST['id']]);
        admin_flash('Question deleted.');
    } else {
        $id       = (int) ($_POST['id'] ?? 0);
        $question = trim($_POST['question'] ?? '');
        $expl     = $_POST['explanation_md'] ?? '';
        $options  = $_POST['options'] ?? [];
        $correct  = (int) ($_POST['correct'] ?? 0);
        if ($id) {
            db()->prepare('UPDATE quiz_questions SET question=?, explanation_md=? WHERE id=?')->execute([$question, $expl, $id]);
            db()->prepare('DELETE FROM quiz_options WHERE question_id=?')->execute([$id]);
            $qid = $id; admin_flash('Question updated.');
        } else {
            db()->prepare('INSERT INTO quiz_questions (quiz_id, question, explanation_md, sort_order) VALUES (?,?,?,?)')
                ->execute([$quizId, $question, $expl, 0]);
            $qid = (int) db()->lastInsertId(); admin_flash('Question added.');
        }
        foreach ($options as $i => $text) {
            if (trim($text) === '') continue;
            db()->prepare('INSERT INTO quiz_options (question_id, option_text, is_correct, sort_order) VALUES (?,?,?,?)')
                ->execute([$qid, $text, $i === $correct ? 1 : 0, $i]);
        }
    }
    redirect('admin/quiz.php?chapter_id=' . $chapterId);
}

$editId = (int) ($_GET['edit'] ?? 0);
$edit = null; $editOptions = [];
if ($editId) {
    $stmt = db()->prepare('SELECT * FROM quiz_questions WHERE id = ?');
    $stmt->execute([$editId]);
    $edit = $stmt->fetch();
    if ($edit) {
        $o = db()->prepare('SELECT * FROM quiz_options WHERE question_id = ? ORDER BY sort_order, id');
        $o->execute([$editId]);
        $editOptions = $o->fetchAll();
    }
}

$questions = db()->prepare('SELECT * FROM quiz_questions WHERE quiz_id = ? ORDER BY sort_order, id');
$questions->execute([$quizId]);
$questions = $questions->fetchAll();

admin_head('Quiz · ' . $chapter['title']);
$correctIndex = 0;
foreach ($editOptions as $i => $o) { if ($o['is_correct']) { $correctIndex = $i; } }
?>
<nav class="mb-3"><a href="<?= url('admin/chapters.php') ?>">← Chapters</a> / Quiz — <?= e($chapter['title']) ?></nav>

<table class="table table-sm bg-white shadow-sm align-middle">
  <thead><tr><th>Question</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($questions as $qq): ?>
    <tr>
      <td><?= e(mb_strimwidth($qq['question'], 0, 80, '…')) ?></td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="?chapter_id=<?= $chapterId ?>&edit=<?= (int) $qq['id'] ?>"><i class="bi bi-pencil"></i></a>
        <form method="post" class="d-inline" onsubmit="return confirm('Delete?')">
          <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="chapter_id" value="<?= $chapterId ?>"><input type="hidden" name="id" value="<?= (int) $qq['id'] ?>">
          <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<div class="card shadow-sm"><div class="card-body">
  <h5><?= $edit ? 'Edit question' : 'New question' ?></h5>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
    <input type="hidden" name="chapter_id" value="<?= $chapterId ?>">
    <div class="mb-2"><label class="form-label">Question</label><input name="question" class="form-control" value="<?= e($edit['question'] ?? '') ?>" required></div>
    <label class="form-label">Options (select the correct one)</label>
    <?php for ($i = 0; $i < 4; $i++): ?>
      <div class="input-group mb-2">
        <div class="input-group-text"><input type="radio" name="correct" value="<?= $i ?>" <?= $i === $correctIndex ? 'checked' : '' ?>></div>
        <input name="options[<?= $i ?>]" class="form-control" placeholder="Option <?= $i + 1 ?>" value="<?= e($editOptions[$i]['option_text'] ?? '') ?>">
      </div>
    <?php endfor; ?>
    <div class="mb-2"><label class="form-label">Explanation (Markdown)</label><textarea name="explanation_md" class="form-control" rows="2"><?= e($edit['explanation_md'] ?? '') ?></textarea></div>
    <button class="btn btn-primary"><?= $edit ? 'Update' : 'Add' ?></button>
    <?php if ($edit): ?><a href="<?= url('admin/quiz.php?chapter_id=' . $chapterId) ?>" class="btn btn-link">Cancel</a><?php endif; ?>
  </form>
</div></div>
<?php admin_foot(); ?>
