<?php
require_once __DIR__ . '/includes/auth.php';

$q = trim($_GET['q'] ?? '');
$topics = $chapters = $problems = [];

if ($q !== '') {
    $like = '%' . $q . '%';

    $stmt = db()->prepare(
        'SELECT t.title, t.slug, t.summary, c.title AS chapter_title
         FROM topics t JOIN chapters c ON c.id = t.chapter_id
         WHERE t.title LIKE ? OR t.summary LIKE ? ORDER BY t.title LIMIT 30'
    );
    $stmt->execute([$like, $like]);
    $topics = $stmt->fetchAll();

    $stmt = db()->prepare('SELECT title, slug, description FROM chapters WHERE title LIKE ? OR description LIKE ? LIMIT 20');
    $stmt->execute([$like, $like]);
    $chapters = $stmt->fetchAll();

    $stmt = db()->prepare('SELECT title, slug, difficulty FROM practice_problems WHERE title LIKE ? LIMIT 20');
    $stmt->execute([$like]);
    $problems = $stmt->fetchAll();
}

$pageTitle = 'Search';
require __DIR__ . '/partials/header.php';
?>
<h2 class="mb-3"><i class="bi bi-search"></i> Search</h2>
<form method="get" class="mb-4">
  <div class="input-group">
    <input type="search" name="q" class="form-control form-control-lg" value="<?= e($q) ?>" placeholder="Search topics, chapters, problems..." autofocus>
    <button class="btn btn-info">Search</button>
  </div>
</form>

<?php if ($q !== ''): ?>
  <?php if (!$topics && !$chapters && !$problems): ?>
    <div class="alert alert-warning">No results for "<?= e($q) ?>".</div>
  <?php endif; ?>

  <?php if ($chapters): ?>
    <h5 class="mt-4">Chapters</h5>
    <div class="list-group shadow-sm mb-3">
      <?php foreach ($chapters as $c): ?>
        <a href="<?= url('chapter.php?slug=' . urlencode($c['slug'])) ?>" class="list-group-item list-group-item-action">
          <span class="fw-semibold"><?= e($c['title']) ?></span> <span class="small text-muted">— <?= e($c['description']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($topics): ?>
    <h5 class="mt-4">Topics</h5>
    <div class="list-group shadow-sm mb-3">
      <?php foreach ($topics as $t): ?>
        <a href="<?= url('topic.php?slug=' . urlencode($t['slug'])) ?>" class="list-group-item list-group-item-action">
          <span class="fw-semibold"><?= e($t['title']) ?></span> <span class="small text-muted">— <?= e($t['chapter_title']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($problems): ?>
    <h5 class="mt-4">Practice problems</h5>
    <div class="list-group shadow-sm">
      <?php foreach ($problems as $p): ?>
        <a href="<?= url('problem.php?slug=' . urlencode($p['slug'])) ?>" class="list-group-item list-group-item-action d-flex justify-content-between">
          <span><?= e($p['title']) ?></span><span class="badge bg-light difficulty-<?= e($p['difficulty']) ?> border text-uppercase"><?= e($p['difficulty']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
