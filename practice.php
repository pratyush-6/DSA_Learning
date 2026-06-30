<?php
require_once __DIR__ . '/includes/auth.php';

$chapterSlug = $_GET['chapter'] ?? '';
$difficulty  = $_GET['difficulty'] ?? '';
$page    = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 25;
$offset  = ($page - 1) * $perPage;

$where = ' WHERE 1=1';
$params = [];
if ($chapterSlug !== '') { $where .= ' AND c.slug = ?'; $params[] = $chapterSlug; }
if (in_array($difficulty, ['easy', 'medium', 'hard'], true)) { $where .= ' AND p.difficulty = ?'; $params[] = $difficulty; }

// Total for pagination.
$countStmt = db()->prepare('SELECT COUNT(*) FROM practice_problems p JOIN chapters c ON c.id = p.chapter_id' . $where);
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();
$pages = max(1, (int) ceil($total / $perPage));

$sql = 'SELECT p.*, c.title AS chapter_title,
               (SELECT COUNT(*) FROM problem_testcases tc WHERE tc.problem_id = p.id) AS tc_count
        FROM practice_problems p JOIN chapters c ON c.id = p.chapter_id' . $where .
       ' ORDER BY FIELD(c.level,"beginner","intermediate","advanced"), c.sort_order, p.sort_order, p.id
         LIMIT ' . $perPage . ' OFFSET ' . $offset;
$stmt = db()->prepare($sql);
$stmt->execute($params);
$problems = $stmt->fetchAll();

// Which of these has the user solved?
$solved = [];
if (is_logged_in() && $problems) {
    $ids = implode(',', array_map(fn($p) => (int) $p['id'], $problems));
    $s = db()->query('SELECT problem_id FROM user_problem_solved WHERE user_id = ' . current_user_id() . " AND problem_id IN ($ids)");
    $solved = array_map('intval', $s->fetchAll(PDO::FETCH_COLUMN));
}

$chapters = db()->query('SELECT slug, title FROM chapters ORDER BY FIELD(level,"beginner","intermediate","advanced"), sort_order')->fetchAll();

// Build a query string for pagination links that preserves filters.
$qs = fn(int $p) => url('practice.php?' . http_build_query(array_filter([
    'chapter' => $chapterSlug, 'difficulty' => $difficulty, 'page' => $p,
])));

$pageTitle = 'Practice Problems';
require __DIR__ . '/partials/header.php';
?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
  <h2 class="mb-0"><i class="bi bi-code-square"></i> Practice Problems</h2>
  <span class="text-muted small"><?= number_format($total) ?> problems</span>
</div>

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
  <?php foreach ($problems as $p): $isSolved = in_array((int) $p['id'], $solved, true); ?>
  <a href="<?= url('problem.php?slug=' . urlencode($p['slug'])) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
      <?php if ($isSolved): ?><i class="bi bi-check-circle-fill text-success" title="Solved"></i>
      <?php else: ?><i class="bi bi-circle text-muted"></i><?php endif; ?>
      <div>
        <span class="fw-semibold"><?= e($p['title']) ?></span>
        <?php if ((int) $p['tc_count'] > 0): ?><span class="badge bg-grad ms-1" title="Has the built-in compiler"><i class="bi bi-terminal"></i> Code</span><?php endif; ?>
        <div class="small text-muted"><?= e($p['chapter_title']) ?></div>
      </div>
    </div>
    <span class="badge bg-light difficulty-<?= e($p['difficulty']) ?> border text-uppercase"><?= e($p['difficulty']) ?></span>
  </a>
  <?php endforeach; ?>
  <?php if (!$problems): ?>
    <div class="empty-state"><i class="bi bi-search"></i><p class="mt-2 mb-0">No problems found for this filter.</p></div>
  <?php endif; ?>
</div>

<?php if ($pages > 1): ?>
<nav class="mt-4" aria-label="Practice pages">
  <ul class="pagination justify-content-center flex-wrap">
    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= e($qs(max(1, $page - 1))) ?>">Previous</a></li>
    <?php
      $start = max(1, $page - 2); $end = min($pages, $page + 2);
      if ($start > 1) echo '<li class="page-item"><a class="page-link" href="' . e($qs(1)) . '">1</a></li><li class="page-item disabled"><span class="page-link">…</span></li>';
      for ($i = $start; $i <= $end; $i++):
    ?>
      <li class="page-item <?= $i === $page ? 'active' : '' ?>"><a class="page-link" href="<?= e($qs($i)) ?>"><?= $i ?></a></li>
    <?php endfor;
      if ($end < $pages) echo '<li class="page-item disabled"><span class="page-link">…</span></li><li class="page-item"><a class="page-link" href="' . e($qs($pages)) . '">' . $pages . '</a></li>';
    ?>
    <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>"><a class="page-link" href="<?= e($qs(min($pages, $page + 1))) ?>">Next</a></li>
  </ul>
</nav>
<?php endif; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
