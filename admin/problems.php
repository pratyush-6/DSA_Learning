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
        db()->prepare('DELETE FROM practice_problems WHERE id = ?')->execute([(int) $_POST['id']]);
        admin_flash('Problem deleted.');
    } else {
        $id    = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $slug  = trim($_POST['slug'] ?? '') ?: slugify($title);
        $data  = [$chapterId, $title, $slug, $_POST['difficulty'] ?? 'easy',
                  $_POST['statement_md'] ?? '', $_POST['constraints_md'] ?? '', $_POST['examples_md'] ?? '',
                  (int) ($_POST['sort_order'] ?? 0)];
        if ($id) {
            $data[] = $id;
            db()->prepare('UPDATE practice_problems SET chapter_id=?, title=?, slug=?, difficulty=?, statement_md=?, constraints_md=?, examples_md=?, sort_order=? WHERE id=?')->execute($data);
            $pid = $id; admin_flash('Problem updated.');
        } else {
            db()->prepare('INSERT INTO practice_problems (chapter_id, title, slug, difficulty, statement_md, constraints_md, examples_md, sort_order) VALUES (?,?,?,?,?,?,?,?)')->execute($data);
            $pid = (int) db()->lastInsertId(); admin_flash('Problem created.');
        }
        // Solutions per language.
        foreach (array_keys(LANGUAGES) as $lang) {
            $code = $_POST['sol_' . $lang] ?? '';
            $expl = $_POST['exp_' . $lang] ?? '';
            db()->prepare('DELETE FROM practice_solutions WHERE problem_id=? AND language=?')->execute([$pid, $lang]);
            if (trim($code) !== '') {
                db()->prepare('INSERT INTO practice_solutions (problem_id, language, code, explanation_md) VALUES (?,?,?,?)')->execute([$pid, $lang, $code, $expl]);
            }
        }
    }
    redirect('admin/problems.php?chapter_id=' . $chapterId);
}

$editId = (int) ($_GET['edit'] ?? 0);
$edit = null; $sols = [];
if ($editId) {
    $stmt = db()->prepare('SELECT * FROM practice_problems WHERE id = ?');
    $stmt->execute([$editId]);
    $edit = $stmt->fetch();
    if ($edit) {
        $s = db()->prepare('SELECT * FROM practice_solutions WHERE problem_id = ?');
        $s->execute([$editId]);
        foreach ($s->fetchAll() as $row) { $sols[$row['language']] = $row; }
    }
}

$stmt = db()->prepare('SELECT * FROM practice_problems WHERE chapter_id = ? ORDER BY sort_order, id');
$stmt->execute([$chapterId]);
$problems = $stmt->fetchAll();

admin_head('Problems · ' . $chapter['title']);
?>
<nav class="mb-3"><a href="<?= url('admin/chapters.php') ?>">← Chapters</a> / Practice — <?= e($chapter['title']) ?></nav>

<table class="table table-sm bg-white shadow-sm align-middle">
  <thead><tr><th>Title</th><th>Difficulty</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($problems as $p): ?>
    <tr>
      <td><?= e($p['title']) ?></td><td><span class="badge bg-light text-dark border"><?= e($p['difficulty']) ?></span></td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="?chapter_id=<?= $chapterId ?>&edit=<?= (int) $p['id'] ?>"><i class="bi bi-pencil"></i></a>
        <form method="post" class="d-inline" onsubmit="return confirm('Delete?')">
          <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="chapter_id" value="<?= $chapterId ?>"><input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
          <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<div class="card shadow-sm"><div class="card-body">
  <h5><?= $edit ? 'Edit problem' : 'New problem' ?></h5>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
    <input type="hidden" name="chapter_id" value="<?= $chapterId ?>">
    <div class="row">
      <div class="col-md-6 mb-2"><label class="form-label">Title</label><input name="title" class="form-control" value="<?= e($edit['title'] ?? '') ?>" required></div>
      <div class="col-md-3 mb-2"><label class="form-label">Difficulty</label>
        <select name="difficulty" class="form-select">
          <?php foreach (['easy','medium','hard'] as $d): ?><option value="<?= $d ?>" <?= ($edit['difficulty'] ?? '') === $d ? 'selected' : '' ?>><?= ucfirst($d) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 mb-2"><label class="form-label">Sort</label><input type="number" name="sort_order" class="form-control" value="<?= (int) ($edit['sort_order'] ?? count($problems)) ?>"></div>
    </div>
    <div class="mb-2"><label class="form-label">Slug (auto if blank)</label><input name="slug" class="form-control" value="<?= e($edit['slug'] ?? '') ?>"></div>
    <div class="mb-2"><label class="form-label">Statement (Markdown)</label><textarea name="statement_md" class="form-control font-monospace" rows="4"><?= e($edit['statement_md'] ?? '') ?></textarea></div>
    <div class="row">
      <div class="col-md-6 mb-2"><label class="form-label">Examples (Markdown)</label><textarea name="examples_md" class="form-control font-monospace" rows="3"><?= e($edit['examples_md'] ?? '') ?></textarea></div>
      <div class="col-md-6 mb-2"><label class="form-label">Constraints (Markdown)</label><textarea name="constraints_md" class="form-control font-monospace" rows="3"><?= e($edit['constraints_md'] ?? '') ?></textarea></div>
    </div>
    <hr>
    <h6>Solutions</h6>
    <ul class="nav nav-tabs" role="tablist">
      <?php $first = true; foreach (LANGUAGES as $k => $l): ?>
        <li class="nav-item"><button class="nav-link <?= $first ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#sol<?= $k ?>" type="button"><?= e($l) ?></button></li>
      <?php $first = false; endforeach; ?>
    </ul>
    <div class="tab-content border border-top-0 p-3 mb-3">
      <?php $first = true; foreach (LANGUAGES as $k => $l): ?>
        <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="sol<?= $k ?>">
          <label class="form-label">Code (<?= e($l) ?>)</label>
          <textarea name="sol_<?= $k ?>" class="form-control font-monospace" rows="8"><?= e($sols[$k]['code'] ?? '') ?></textarea>
          <label class="form-label mt-2">Explanation (Markdown)</label>
          <textarea name="exp_<?= $k ?>" class="form-control" rows="2"><?= e($sols[$k]['explanation_md'] ?? '') ?></textarea>
        </div>
      <?php $first = false; endforeach; ?>
    </div>
    <button class="btn btn-primary"><?= $edit ? 'Update problem' : 'Create problem' ?></button>
    <?php if ($edit): ?><a href="<?= url('admin/problems.php?chapter_id=' . $chapterId) ?>" class="btn btn-link">Cancel</a><?php endif; ?>
  </form>
</div></div>
<?php admin_foot(); ?>
