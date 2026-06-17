<?php
require_once __DIR__ . '/_layout.php';

$chapterId = (int) ($_GET['chapter_id'] ?? ($_POST['chapter_id'] ?? 0));
$stmt = db()->prepare('SELECT * FROM chapters WHERE id = ?');
$stmt->execute([$chapterId]);
$chapter = $stmt->fetch();
if (!$chapter) { redirect('admin/chapters.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        db()->prepare('DELETE FROM interview_questions WHERE id = ?')->execute([(int) $_POST['id']]);
        admin_flash('Question deleted.');
    } else {
        $id   = (int) ($_POST['id'] ?? 0);
        $data = [$chapterId, $_POST['type'] ?? 'conceptual', $_POST['difficulty'] ?? 'easy',
                 trim($_POST['question'] ?? ''), $_POST['answer_md'] ?? '', (int) ($_POST['sort_order'] ?? 0)];
        if ($id) {
            $data[] = $id;
            db()->prepare('UPDATE interview_questions SET chapter_id=?, type=?, difficulty=?, question=?, answer_md=?, sort_order=? WHERE id=?')->execute($data);
            $qid = $id;
            admin_flash('Question updated.');
        } else {
            db()->prepare('INSERT INTO interview_questions (chapter_id, type, difficulty, question, answer_md, sort_order) VALUES (?,?,?,?,?,?)')->execute($data);
            $qid = (int) db()->lastInsertId();
            admin_flash('Question added.');
        }
        // Companies (comma-separated).
        db()->prepare('DELETE FROM interview_question_company WHERE question_id = ?')->execute([$qid]);
        foreach (array_filter(array_map('trim', explode(',', $_POST['companies'] ?? ''))) as $name) {
            $slug = slugify($name);
            $c = db()->prepare('SELECT id FROM companies WHERE slug = ?'); $c->execute([$slug]);
            $cid = $c->fetchColumn();
            if (!$cid) { db()->prepare('INSERT INTO companies (name, slug) VALUES (?,?)')->execute([$name, $slug]); $cid = db()->lastInsertId(); }
            db()->prepare('INSERT IGNORE INTO interview_question_company (question_id, company_id) VALUES (?,?)')->execute([$qid, (int) $cid]);
        }
    }
    redirect('admin/interview.php?chapter_id=' . $chapterId);
}

$editId = (int) ($_GET['edit'] ?? 0);
$edit = null; $editCompanies = '';
if ($editId) {
    $stmt = db()->prepare('SELECT * FROM interview_questions WHERE id = ?');
    $stmt->execute([$editId]);
    $edit = $stmt->fetch();
    if ($edit) {
        $cs = db()->prepare('SELECT co.name FROM interview_question_company iqc JOIN companies co ON co.id=iqc.company_id WHERE iqc.question_id=?');
        $cs->execute([$editId]);
        $editCompanies = implode(', ', $cs->fetchAll(PDO::FETCH_COLUMN));
    }
}

$stmt = db()->prepare('SELECT * FROM interview_questions WHERE chapter_id = ? ORDER BY sort_order, id');
$stmt->execute([$chapterId]);
$questions = $stmt->fetchAll();

admin_head('Interview · ' . $chapter['title']);
?>
<nav class="mb-3"><a href="<?= url('admin/chapters.php') ?>">← Chapters</a> / Interview Q&amp;A — <?= e($chapter['title']) ?></nav>

<table class="table table-sm bg-white shadow-sm align-middle">
  <thead><tr><th>Type</th><th>Diff</th><th>Question</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($questions as $q): ?>
    <tr>
      <td><?= e($q['type']) ?></td><td><?= e($q['difficulty']) ?></td>
      <td><?= e(mb_strimwidth($q['question'], 0, 70, '…')) ?></td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="?chapter_id=<?= $chapterId ?>&edit=<?= (int) $q['id'] ?>"><i class="bi bi-pencil"></i></a>
        <form method="post" class="d-inline" onsubmit="return confirm('Delete?')">
          <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="chapter_id" value="<?= $chapterId ?>"><input type="hidden" name="id" value="<?= (int) $q['id'] ?>">
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
    <div class="row">
      <div class="col-md-3 mb-2"><label class="form-label">Type</label>
        <select name="type" class="form-select">
          <option value="conceptual" <?= ($edit['type'] ?? '') === 'conceptual' ? 'selected' : '' ?>>Conceptual</option>
          <option value="coding" <?= ($edit['type'] ?? '') === 'coding' ? 'selected' : '' ?>>Coding</option>
        </select>
      </div>
      <div class="col-md-3 mb-2"><label class="form-label">Difficulty</label>
        <select name="difficulty" class="form-select">
          <?php foreach (['easy','medium','hard'] as $d): ?><option value="<?= $d ?>" <?= ($edit['difficulty'] ?? '') === $d ? 'selected' : '' ?>><?= ucfirst($d) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6 mb-2"><label class="form-label">Companies (comma-separated)</label><input name="companies" class="form-control" value="<?= e($editCompanies) ?>"></div>
    </div>
    <div class="mb-2"><label class="form-label">Question</label><input name="question" class="form-control" value="<?= e($edit['question'] ?? '') ?>" required></div>
    <div class="mb-2"><label class="form-label">Answer (Markdown)</label><textarea name="answer_md" class="form-control font-monospace" rows="6"><?= e($edit['answer_md'] ?? '') ?></textarea></div>
    <button class="btn btn-primary"><?= $edit ? 'Update' : 'Add' ?></button>
    <?php if ($edit): ?><a href="<?= url('admin/interview.php?chapter_id=' . $chapterId) ?>" class="btn btn-link">Cancel</a><?php endif; ?>
  </form>
</div></div>
<?php admin_foot(); ?>
