<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/groups.php';
require_login();

$uid  = current_user_id();
$user = current_user();
record_activity($uid);

// ---- Time-range filter ----------------------------------------------------
$ranges = ['daily' => 1, 'weekly' => 7, 'monthly' => 30, 'all' => 0];
$range  = isset($_GET['range'], $ranges[$_GET['range']]) ? $_GET['range'] : 'all';
$rangeDays = $ranges[$range];
$startDate = $rangeDays ? date('Y-m-d', strtotime('-' . ($rangeDays - 1) . ' days')) : null;
$rangeLabel = ['daily' => 'Today', 'weekly' => 'This week', 'monthly' => 'This month', 'all' => 'All time'][$range];

function count_since(int $uid, string $table, string $col, ?string $start): int
{
    if ($start) {
        $s = db()->prepare("SELECT COUNT(*) FROM {$table} WHERE user_id = ? AND {$col} >= ?");
        $s->execute([$uid, $start . ' 00:00:00']);
    } else {
        $s = db()->prepare("SELECT COUNT(*) FROM {$table} WHERE user_id = ?");
        $s->execute([$uid]);
    }
    return (int) $s->fetchColumn();
}

$modulesRange   = count_since($uid, 'user_progress', 'completed_at', $startDate);
$questionsRange = count_since($uid, 'user_problem_solved', 'solved_at', $startDate);
$overall = overall_progress($uid);
$streak  = current_streak($uid);
$best    = longest_streak($uid);

$modulesAll   = $overall['done'];
$questionsAll = (int) db()->query("SELECT COUNT(*) FROM user_problem_solved WHERE user_id = {$uid}")->fetchColumn();
$quizCount    = (int) db()->query("SELECT COUNT(*) FROM user_quiz_attempts WHERE user_id = {$uid}")->fetchColumn();

// ---- Group performance ----------------------------------------------------
$grp = user_group($uid);
$groupRank = $groupTotal = 0;
if ($grp) {
    $ms = group_member_stats((int) $grp['id']);
    $groupTotal = count($ms);
    foreach ($ms as $i => $m) { if ((int) $m['id'] === $uid) { $groupRank = $i + 1; break; } }
}

// ---- Per-level progress ---------------------------------------------------
$levelStats = [];
foreach (LEVELS as $key => $label) { $levelStats[$key] = ['label' => $label] + level_progress($uid, $key); }

// ---- Activity trend (chart) ----------------------------------------------
$chartDays = max($rangeDays ?: 30, 7);
$days = [];
for ($i = $chartDays - 1; $i >= 0; $i--) { $days[date('Y-m-d', strtotime("-{$i} days"))] = ['m' => 0, 'q' => 0]; }
$since = array_key_first($days) . ' 00:00:00';

$st = db()->prepare('SELECT DATE(completed_at) d, COUNT(*) c FROM user_progress WHERE user_id=? AND completed_at>=? GROUP BY DATE(completed_at)');
$st->execute([$uid, $since]);
foreach ($st as $r) { if (isset($days[$r['d']])) $days[$r['d']]['m'] = (int) $r['c']; }
$st = db()->prepare('SELECT DATE(solved_at) d, COUNT(*) c FROM user_problem_solved WHERE user_id=? AND solved_at>=? GROUP BY DATE(solved_at)');
$st->execute([$uid, $since]);
foreach ($st as $r) { if (isset($days[$r['d']])) $days[$r['d']]['q'] = (int) $r['c']; }

$trendLabels = array_map(fn($d) => date('M j', strtotime($d)), array_keys($days));
$trendModules = array_map(fn($v) => $v['m'], array_values($days));
$trendQuestions = array_map(fn($v) => $v['q'], array_values($days));

// ---- Achievements ---------------------------------------------------------
$stmt = db()->prepare('SELECT achievement_id FROM user_achievements WHERE user_id = ?');
$stmt->execute([$uid]);
$earnedIds = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
$allAch = db()->query('SELECT * FROM achievements ORDER BY id')->fetchAll();

// ---- Continue learning ----------------------------------------------------
$nextStmt = db()->prepare(
    'SELECT t.title, t.slug, c.title AS chapter_title
     FROM topics t JOIN chapters c ON c.id = t.chapter_id
     WHERE t.id NOT IN (SELECT topic_id FROM user_progress WHERE user_id = ? AND status="completed")
     ORDER BY FIELD(c.level,"beginner","intermediate","advanced"), c.sort_order, t.sort_order, t.id LIMIT 1'
);
$nextStmt->execute([$uid]);
$nextTopic = $nextStmt->fetch();

$pageTitle = 'Dashboard';
require __DIR__ . '/partials/header.php';
?>
<div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
  <div>
    <h2 class="mb-1">Welcome back, <?= e($user['name']) ?> 👋</h2>
    <p class="text-muted mb-0">Here's your learning snapshot.</p>
  </div>
  <div class="segmented" role="tablist" aria-label="Time range">
    <?php foreach (['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'all' => 'All-time'] as $k => $lbl): ?>
      <a class="<?= $range === $k ? 'active' : '' ?>" href="?range=<?= $k ?>"><?= $lbl ?></a>
    <?php endforeach; ?>
  </div>
</div>

<!-- Analytics cards -->
<div class="row g-3 mb-4">
  <div class="col-6 col-lg col-xl">
    <div class="card stat-card lift h-100 fade-in-up"><div class="card-body">
      <div class="stat-icon bg-cyan mb-3"><i class="bi bi-patch-check"></i></div>
      <div class="stat-value"><?= $questionsRange ?></div>
      <div class="stat-label">Questions solved</div>
      <div class="small text-muted mt-1"><?= e($rangeLabel) ?> · <?= $questionsAll ?> all-time</div>
    </div></div>
  </div>
  <div class="col-6 col-lg col-xl">
    <div class="card stat-card lift h-100 fade-in-up d1"><div class="card-body">
      <div class="stat-icon bg-indigo mb-3"><i class="bi bi-journal-check"></i></div>
      <div class="stat-value"><?= $modulesRange ?></div>
      <div class="stat-label">Modules completed</div>
      <div class="small text-muted mt-1"><?= e($rangeLabel) ?> · <?= $modulesAll ?> all-time</div>
    </div></div>
  </div>
  <div class="col-6 col-lg col-xl">
    <div class="card stat-card lift h-100 fade-in-up d2"><div class="card-body">
      <div class="stat-icon bg-amber mb-3"><i class="bi bi-fire"></i></div>
      <div class="stat-value"><?= $streak ?></div>
      <div class="stat-label">Day streak</div>
      <div class="small text-muted mt-1">Best: <?= $best ?> days</div>
    </div></div>
  </div>
  <div class="col-6 col-lg col-xl">
    <div class="card stat-card lift h-100 fade-in-up d3"><div class="card-body">
      <div class="stat-icon bg-rose mb-3"><i class="bi bi-people-fill"></i></div>
      <?php if ($grp): ?>
        <div class="stat-value">#<?= $groupRank ?><span class="fs-6 text-muted">/<?= $groupTotal ?></span></div>
        <div class="stat-label">Group rank</div>
        <div class="small text-muted mt-1 text-truncate"><?= e($grp['name']) ?></div>
      <?php else: ?>
        <div class="stat-value">—</div>
        <div class="stat-label">Group performance</div>
        <a class="small" href="<?= url('groups.php') ?>">Join a group →</a>
      <?php endif; ?>
    </div></div>
  </div>
  <div class="col-6 col-lg col-xl">
    <div class="card stat-card lift h-100 fade-in-up d4"><div class="card-body d-flex align-items-center gap-3">
      <div class="ring position-relative" style="--val:<?= $overall['percent'] ?>"><span><?= $overall['percent'] ?>%</span></div>
      <div>
        <div class="stat-label">Overall progress</div>
        <div class="small text-muted"><?= $overall['done'] ?>/<?= $overall['total'] ?> modules</div>
      </div>
    </div></div>
  </div>
</div>

<?php if ($nextTopic): ?>
<div class="card lift mb-4 fade-in-up"><div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div><div class="text-muted small text-uppercase" style="letter-spacing:.05em">Continue learning</div>
    <h5 class="mb-0"><?= e($nextTopic['title']) ?> <span class="text-muted fw-normal">· <?= e($nextTopic['chapter_title']) ?></span></h5></div>
  <a href="<?= url('topic.php?slug=' . urlencode($nextTopic['slug'])) ?>" class="btn btn-primary">Resume <i class="bi bi-arrow-right"></i></a>
</div></div>
<?php endif; ?>

<div class="row g-4 mb-4">
  <div class="col-lg-8">
    <div class="card h-100"><div class="card-body">
      <h5 class="mb-3">Activity over time</h5>
      <div id="trend-skeleton" class="skeleton" style="height:260px"></div>
      <canvas id="trendChart" height="120" class="d-none"></canvas>
    </div></div>
  </div>
  <div class="col-lg-4">
    <div class="card h-100"><div class="card-body">
      <h5 class="mb-3">Progress by level</h5>
      <?php foreach ($levelStats as $key => $st2): ?>
        <div class="d-flex justify-content-between mb-1 small">
          <span class="badge level-<?= $key ?>"><?= e($st2['label']) ?></span>
          <span class="text-muted"><?= $st2['done'] ?>/<?= $st2['total'] ?> · <?= $st2['percent'] ?>%</span>
        </div>
        <div class="progress mb-3" style="height:8px"><div class="progress-bar" style="width:<?= $st2['percent'] ?>%"></div></div>
      <?php endforeach; ?>
    </div></div>
  </div>
</div>

<div class="card mb-4"><div class="card-body">
  <h5 class="mb-3"><i class="bi bi-trophy text-warning"></i> Achievements</h5>
  <div class="row g-3">
    <?php foreach ($allAch as $a): $got = in_array((int) $a['id'], $earnedIds, true); ?>
      <div class="col-6 col-md-3 col-lg-2 text-center">
        <div class="p-3 rounded-3 h-100 <?= $got ? '' : 'opacity-50' ?>" style="background:var(--surface-2)">
          <i class="bi <?= e($a['icon'] ?: 'bi-award') ?> fs-2 <?= $got ? 'text-warning' : 'text-muted' ?>"></i>
          <div class="small fw-semibold mt-1"><?= e($a['title']) ?></div>
          <div class="text-muted" style="font-size:.72rem"><?= e($a['description']) ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
  const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
  Chart.defaults.color = isDark ? '#93a0ba' : '#64748b';
  Chart.defaults.borderColor = isDark ? '#263149' : '#e6e8ef';
  const sk = document.getElementById('trend-skeleton');
  const cv = document.getElementById('trendChart');
  if (window.Chart && cv) {
    if (sk) sk.remove();
    cv.classList.remove('d-none');
    new Chart(cv, {
      type: 'line',
      data: {
        labels: <?= json_encode($trendLabels) ?>,
        datasets: [
          { label: 'Modules completed', data: <?= json_encode($trendModules) ?>, borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,.15)', fill: true, tension: .35 },
          { label: 'Questions solved', data: <?= json_encode($trendQuestions) ?>, borderColor: '#06b6d4', backgroundColor: 'rgba(6,182,212,.12)', fill: true, tension: .35 },
        ]
      },
      options: { responsive: true, interaction: { mode: 'index', intersect: false },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
  }
})();
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
