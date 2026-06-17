<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
$uid = current_user_id();

$stmt = db()->prepare(
    'SELECT t.title, t.slug, t.summary, c.title AS chapter_title, b.created_at
     FROM bookmarks b
     JOIN topics t ON t.id = b.topic_id
     JOIN chapters c ON c.id = t.chapter_id
     WHERE b.user_id = ? ORDER BY b.created_at DESC'
);
$stmt->execute([$uid]);
$rows = $stmt->fetchAll();

$pageTitle = 'My Bookmarks';
require __DIR__ . '/partials/header.php';
?>
<h2 class="mb-3"><i class="bi bi-bookmark-star"></i> My Bookmarks</h2>
<?php if ($rows): ?>
  <div class="list-group shadow-sm">
    <?php foreach ($rows as $r): ?>
      <a href="<?= url('topic.php?slug=' . urlencode($r['slug'])) ?>" class="list-group-item list-group-item-action">
        <div class="fw-semibold"><?= e($r['title']) ?></div>
        <div class="small text-muted"><?= e($r['chapter_title']) ?> &middot; <?= e($r['summary']) ?></div>
      </a>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="alert alert-info">No bookmarks yet. Open any topic and click <strong>Bookmark</strong> to save it here.</div>
<?php endif; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
