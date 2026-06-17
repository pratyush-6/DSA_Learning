<?php
require_once __DIR__ . '/_layout.php';

// Handle create / update / delete.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        db()->prepare('DELETE FROM chapters WHERE id = ?')->execute([(int) $_POST['id']]);
        admin_flash('Chapter deleted.');
    } else {
        $id    = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $slug  = trim($_POST['slug'] ?? '') ?: slugify($title);
        $data  = [$title, $slug, $_POST['level'] ?? 'beginner',
                  trim($_POST['description'] ?? ''), trim($_POST['icon'] ?? 'bi-journal-code'),
                  (int) ($_POST['sort_order'] ?? 0)];
        if ($id) {
            $data[] = $id;
            db()->prepare('UPDATE chapters SET title=?, slug=?, level=?, description=?, icon=?, sort_order=? WHERE id=?')->execute($data);
            admin_flash('Chapter updated.');
        } else {
            db()->prepare('INSERT INTO chapters (title, slug, level, description, icon, sort_order) VALUES (?,?,?,?,?,?)')->execute($data);
            admin_flash('Chapter created.');
        }
    }
    redirect('admin/chapters.php');
}

$editId = (int) ($_GET['edit'] ?? 0);
$edit = null;
if ($editId) {
    $stmt = db()->prepare('SELECT * FROM chapters WHERE id = ?');
    $stmt->execute([$editId]);
    $edit = $stmt->fetch();
}

$chapters = db()->query(
    'SELECT c.*, (SELECT COUNT(*) FROM topics WHERE chapter_id=c.id) AS topics
     FROM chapters c ORDER BY FIELD(level,"beginner","intermediate","advanced"), sort_order, id'
)->fetchAll();

admin_head('Chapters');
?>
<div class="row">
  <div class="col-lg-7">
    <h4 class="mb-3">Chapters</h4>
    <table class="table table-sm bg-white shadow-sm align-middle">
      <thead><tr><th>#</th><th>Title</th><th>Level</th><th>Topics</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($chapters as $c): ?>
        <tr>
          <td><?= (int) $c['sort_order'] ?></td>
          <td><?= e($c['title']) ?></td>
          <td><span class="badge bg-secondary"><?= e($c['level']) ?></span></td>
          <td><a href="<?= url('admin/topics.php?chapter_id=' . (int) $c['id']) ?>"><?= (int) $c['topics'] ?> topics</a></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="?edit=<?= (int) $c['id'] ?>"><i class="bi bi-pencil"></i></a>
            <a class="btn btn-sm btn-outline-dark" href="<?= url('admin/interview.php?chapter_id=' . (int) $c['id']) ?>">IQ</a>
            <a class="btn btn-sm btn-outline-dark" href="<?= url('admin/problems.php?chapter_id=' . (int) $c['id']) ?>">P</a>
            <a class="btn btn-sm btn-outline-dark" href="<?= url('admin/quiz.php?chapter_id=' . (int) $c['id']) ?>">Q</a>
            <form method="post" class="d-inline" onsubmit="return confirm('Delete chapter and ALL its content?')">
              <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="col-lg-5">
    <div class="card shadow-sm"><div class="card-body">
      <h5><?= $edit ? 'Edit chapter' : 'New chapter' ?></h5>
      <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
        <div class="mb-2"><label class="form-label">Title</label><input name="title" class="form-control" value="<?= e($edit['title'] ?? '') ?>" required></div>
        <div class="mb-2"><label class="form-label">Slug <span class="text-muted small">(auto if blank)</span></label><input name="slug" class="form-control" value="<?= e($edit['slug'] ?? '') ?>"></div>
        <div class="mb-2"><label class="form-label">Level</label>
          <select name="level" class="form-select">
            <?php foreach (LEVELS as $k => $l): ?><option value="<?= $k ?>" <?= ($edit['level'] ?? '') === $k ? 'selected' : '' ?>><?= e($l) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="mb-2"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"><?= e($edit['description'] ?? '') ?></textarea></div>
        <div class="row">
          <div class="col mb-2"><label class="form-label">Icon (bi-*)</label><input name="icon" class="form-control" value="<?= e($edit['icon'] ?? 'bi-journal-code') ?>"></div>
          <div class="col mb-2"><label class="form-label">Sort</label><input type="number" name="sort_order" class="form-control" value="<?= (int) ($edit['sort_order'] ?? 0) ?>"></div>
        </div>
        <button class="btn btn-primary w-100"><?= $edit ? 'Update' : 'Create' ?></button>
        <?php if ($edit): ?><a href="<?= url('admin/chapters.php') ?>" class="btn btn-link w-100">Cancel</a><?php endif; ?>
      </form>
    </div></div>
  </div>
</div>
<?php admin_foot(); ?>
