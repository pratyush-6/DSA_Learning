<?php
require_once __DIR__ . '/_layout.php';

$topicId = (int) ($_GET['topic_id'] ?? ($_POST['topic_id'] ?? 0));
$stmt = db()->prepare('SELECT t.*, c.id AS cid FROM topics t JOIN chapters c ON c.id=t.chapter_id WHERE t.id = ?');
$stmt->execute([$topicId]);
$topic = $stmt->fetch();
if (!$topic) { redirect('admin/chapters.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        db()->prepare('DELETE FROM code_snippets WHERE id = ?')->execute([(int) $_POST['id']]);
        admin_flash('Snippet deleted.');
    } else {
        $id   = (int) ($_POST['id'] ?? 0);
        $data = [$topicId, $_POST['language'] ?? 'php', trim($_POST['label'] ?? 'Example'),
                 $_POST['code'] ?? '', $_POST['explanation_md'] ?? '', (int) ($_POST['sort_order'] ?? 0)];
        if ($id) {
            $data[] = $id;
            db()->prepare('UPDATE code_snippets SET topic_id=?, language=?, label=?, code=?, explanation_md=?, sort_order=? WHERE id=?')->execute($data);
            admin_flash('Snippet updated.');
        } else {
            db()->prepare('INSERT INTO code_snippets (topic_id, language, label, code, explanation_md, sort_order) VALUES (?,?,?,?,?,?)')->execute($data);
            admin_flash('Snippet added.');
        }
    }
    redirect('admin/code.php?topic_id=' . $topicId);
}

$editId = (int) ($_GET['edit'] ?? 0);
$edit = null;
if ($editId) {
    $stmt = db()->prepare('SELECT * FROM code_snippets WHERE id = ?');
    $stmt->execute([$editId]);
    $edit = $stmt->fetch();
}

$stmt = db()->prepare('SELECT * FROM code_snippets WHERE topic_id = ? ORDER BY sort_order, id');
$stmt->execute([$topicId]);
$snippets = $stmt->fetchAll();

admin_head('Code · ' . $topic['title']);
?>
<nav class="mb-3"><a href="<?= url('admin/topics.php?chapter_id=' . (int) $topic['cid']) ?>">← Topics</a> / Code for “<?= e($topic['title']) ?>”</nav>

<table class="table table-sm bg-white shadow-sm align-middle">
  <thead><tr><th>Lang</th><th>Label</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($snippets as $s): ?>
    <tr>
      <td><span class="badge bg-dark"><?= e(lang_label($s['language'])) ?></span></td>
      <td><?= e($s['label']) ?></td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="?topic_id=<?= $topicId ?>&edit=<?= (int) $s['id'] ?>"><i class="bi bi-pencil"></i></a>
        <form method="post" class="d-inline" onsubmit="return confirm('Delete snippet?')">
          <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="topic_id" value="<?= $topicId ?>"><input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
          <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<div class="card shadow-sm"><div class="card-body">
  <h5><?= $edit ? 'Edit snippet' : 'Add snippet' ?></h5>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
    <input type="hidden" name="topic_id" value="<?= $topicId ?>">
    <div class="row">
      <div class="col-md-4 mb-2"><label class="form-label">Language</label>
        <select name="language" class="form-select">
          <?php foreach (LANGUAGES as $k => $l): ?><option value="<?= $k ?>" <?= ($edit['language'] ?? '') === $k ? 'selected' : '' ?>><?= e($l) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6 mb-2"><label class="form-label">Label</label><input name="label" class="form-control" value="<?= e($edit['label'] ?? 'Example') ?>"></div>
      <div class="col-md-2 mb-2"><label class="form-label">Sort</label><input type="number" name="sort_order" class="form-control" value="<?= (int) ($edit['sort_order'] ?? count($snippets)) ?>"></div>
    </div>
    <div class="mb-2"><label class="form-label">Code</label><textarea name="code" class="form-control font-monospace" rows="10"><?= e($edit['code'] ?? '') ?></textarea></div>
    <div class="mb-2"><label class="form-label">Explanation (Markdown)</label><textarea name="explanation_md" class="form-control" rows="3"><?= e($edit['explanation_md'] ?? '') ?></textarea></div>
    <button class="btn btn-primary"><?= $edit ? 'Update' : 'Add' ?></button>
    <?php if ($edit): ?><a href="<?= url('admin/code.php?topic_id=' . $topicId) ?>" class="btn btn-link">Cancel</a><?php endif; ?>
  </form>
</div></div>
<?php admin_foot(); ?>
