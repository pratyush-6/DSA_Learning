<?php
require_once __DIR__ . '/includes/auth.php';

$chapterSlug = $_GET['chapter'] ?? '';
$difficulty  = $_GET['difficulty'] ?? '';

$sql    = 'SELECT p.*, c.title AS chapter_title, c.slug AS chapter_slug
           FROM practice_problems p JOIN chapters c ON c.id = p.chapter_id WHERE 1=1';
$params = [];
if ($chapterSlug !== '') { $sql .= ' AND c.slug = ?';     $params[] = $chapterSlug; }
if (in_array($difficulty, ['easy', 'medium', 'hard'], true)) { $sql .= ' AND p.difficulty = ?'; $params[] = $difficulty; }
$sql .= ' ORDER BY FIELD(c.level,"beginner","intermediate","advanced"), c.sort_order, p.sort_order, p.id';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$problems = $stmt->fetchAll();

$chapters = db()->query('SELECT slug, title FROM chapters ORDER BY FIELD(level,"beginner","intermediate","advanced"), sort_order')->fetchAll();

$pageTitle = 'Practice Problems';
require __DIR__ . '/partials/header.php';
?>
<h2 class="mb-3"><i class="bi bi-code-square"></i> Practice Problems</h2>

<form class="row g-2 mb-4" method="get">
  <div class="col-md-5">
    <select name="chapter" class="form-select" onchange="this.form.submit()">
      <option value="">All chapters</option>
      <?php foreach ($chapters as $c): ?>
        <option value="<?= e($c['slug']) ?>" <?= $chapterSlug === $c['slug'] ? 'selected' : '' ?>><?= e($c['title']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <select name="difficulty" class="form-select" onchange="this.form.submit()">
      <option value="">All difficulties</option>
      <?php foreach (['easy', 'medium', 'hard'] as $d): ?>
        <option value="<?= $d ?>" <?= $difficulty === $d ? 'selected' : '' ?>><?= ucfirst($d) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-2"><a href="<?= url('practice.php') ?>" class="btn btn-outline-secondary w-100">Reset</a></div>
</form>

<div class="list-group shadow-sm">
  <?php foreach ($problems as $p): ?>
  <a href="<?= url('problem.php?slug=' . urlencode($p['slug'])) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
    <div>
      <span class="fw-semibold"><?= e($p['title']) ?></span>
      <div class="small text-muted"><?= e($p['chapter_title']) ?></div>
    </div>
    <span class="badge bg-light difficulty-<?= e($p['difficulty']) ?> border text-uppercase"><?= e($p['difficulty']) ?></span>
  </a>
  <?php endforeach; ?>
  <?php if (!$problems): ?><div class="list-group-item text-muted">No problems found for this filter.</div><?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
