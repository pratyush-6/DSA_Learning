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
        db()->prepare('DELETE FROM topics WHERE id = ?')->execute([(int) $_POST['id']]);
        admin_flash('Topic deleted.');
    } else {
        $id    = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $slug  = trim($_POST['slug'] ?? '') ?: slugify($chapter['slug'] . '-' . $title);
        $data  = [$chapterId, $title, $slug, trim($_POST['summary'] ?? ''),
                  $_POST['theory_md'] ?? '', $_POST['real_world_md'] ?? '', $_POST['complexity_md'] ?? '',
                  (int) ($_POST['sort_order'] ?? 0)];
        if ($id) {
            $data[] = $id;
            db()->prepare('UPDATE topics SET chapter_id=?, title=?, slug=?, summary=?, theory_md=?, real_world_md=?, complexity_md=?, sort_order=? WHERE id=?')->execute($data);
            admin_flash('Topic updated.');
        } else {
            db()->prepare('INSERT INTO topics (chapter_id, title, slug, summary, theory_md, real_world_md, complexity_md, sort_order) VALUES (?,?,?,?,?,?,?,?)')->execute($data);
            admin_flash('Topic created.');
        }
    }
    redirect('admin/topics.php?chapter_id=' . $chapterId);
}

$editId = (int) ($_GET['edit'] ?? 0);
$edit = null;
if ($editId) {
    $stmt = db()->prepare('SELECT * FROM topics WHERE id = ?');
    $stmt->execute([$editId]);
    $edit = $stmt->fetch();
}

$stmt = db()->prepare('SELECT * FROM topics WHERE chapter_id = ? ORDER BY sort_order, id');
$stmt->execute([$chapterId]);
$topics = $stmt->fetchAll();

admin_head('Topics · ' . $chapter['title']);
?>
<nav class="mb-3"><a href="<?= url('admin/chapters.php') ?>">← Chapters</a> / <?= e($chapter['title']) ?></nav>
<h4 class="mb-3">Topics in “<?= e($chapter['title']) ?>”</h4>

<table class="table table-sm bg-white shadow-sm align-middle">
  <thead><tr><th>#</th><th>Title</th><th>Slug</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($topics as $t): ?>
    <tr>
      <td><?= (int) $t['sort_order'] ?></td>
      <td><?= e($t['title']) ?></td>
      <td class="small text-muted"><?= e($t['slug']) ?></td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-dark" href="<?= url('admin/code.php?topic_id=' . (int) $t['id']) ?>"><i class="bi bi-code-slash"></i> Code</a>
        <a class="btn btn-sm btn-outline-primary" href="?chapter_id=<?= $chapterId ?>&edit=<?= (int) $t['id'] ?>"><i class="bi bi-pencil"></i></a>
        <form method="post" class="d-inline" onsubmit="return confirm('Delete topic?')">
          <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="chapter_id" value="<?= $chapterId ?>"><input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
          <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<div class="card shadow-sm"><div class="card-body">
  <h5><?= $edit ? 'Edit topic' : 'New topic' ?></h5>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
    <input type="hidden" name="chapter_id" value="<?= $chapterId ?>">
    <div class="row">
      <div class="col-md-8 mb-2"><label class="form-label">Title</label><input name="title" class="form-control" value="<?= e($edit['title'] ?? '') ?>" required></div>
      <div class="col-md-4 mb-2"><label class="form-label">Sort</label><input type="number" name="sort_order" class="form-control" value="<?= (int) ($edit['sort_order'] ?? count($topics)) ?>"></div>
    </div>
    <div class="mb-2"><label class="form-label">Slug <span class="text-muted small">(auto if blank)</span></label><input name="slug" class="form-control" value="<?= e($edit['slug'] ?? '') ?>"></div>
    <div class="mb-2"><label class="form-label">Summary</label><input name="summary" class="form-control" value="<?= e($edit['summary'] ?? '') ?>"></div>
    <div class="mb-2"><label class="form-label">Theory (Markdown)</label><textarea name="theory_md" class="form-control font-monospace" rows="8"><?= e($edit['theory_md'] ?? '') ?></textarea></div>
    <div class="mb-2"><label class="form-label">Complexity (Markdown)</label><textarea name="complexity_md" class="form-control font-monospace" rows="4"><?= e($edit['complexity_md'] ?? '') ?></textarea></div>
    <div class="mb-2"><label class="form-label">Real-world examples (Markdown)</label><textarea name="real_world_md" class="form-control font-monospace" rows="5"><?= e($edit['real_world_md'] ?? '') ?></textarea></div>
    <button class="btn btn-primary"><?= $edit ? 'Update topic' : 'Create topic' ?></button>
    <?php if ($edit): ?><a href="<?= url('admin/topics.php?chapter_id=' . $chapterId) ?>" class="btn btn-link">Cancel</a><?php endif; ?>
  </form>
</div></div>
<?php admin_foot(); ?>
