<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/groups.php';
require_login();

$uid   = current_user_id();
$group = user_group($uid);
if (!$group) {
    redirect('groups.php');
}
$gid = (int) $group['id'];

$members = group_member_stats($gid);
$history = group_completion_history($gid);
$trend   = group_trend_data($gid);

// Build chart datasets.
$labels         = array_map(fn($m) => $m['name'], $members);
$modulesData    = array_map(fn($m) => (int) $m['modules_completed'], $members);
$questionsData  = array_map(fn($m) => (int) $m['questions_solved'], $members);
$quizzesData    = array_map(fn($m) => (int) $m['quizzes_taken'], $members);

// Distinct colors per member for the trend lines.
$palette = ['#0dcaf0', '#fd7e14', '#198754', '#dc3545', '#6610f2', '#d63384', '#20c997', '#ffc107'];
$trendDatasets = [];
$ci = 0;
foreach ($trend['series'] as $s) {
    $color = $palette[$ci % count($palette)];
    $trendDatasets[] = [
        'label'           => $s['name'],
        'data'            => $s['cumulative'],
        'borderColor'     => $color,
        'backgroundColor' => $color,
        'tension'         => 0.25,
        'fill'            => false,
    ];
    $ci++;
}

$hasActivity = array_sum($modulesData) > 0 || array_sum($questionsData) > 0;

$pageTitle = 'Group Comparison Dashboard';
require __DIR__ . '/partials/header.php';
?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
  <div>
    <h2 class="mb-0"><i class="bi bi-bar-chart-line"></i> <?= e($group['name']) ?> — Comparison</h2>
    <span class="text-muted">Join code <span class="badge bg-dark"><?= e($group['join_code']) ?></span> · <?= count($members) ?> member(s)</span>
  </div>
  <a href="<?= url('groups.php') ?>" class="btn btn-outline-secondary"><i class="bi bi-people"></i> Group</a>
</div>

<?php if (!$hasActivity): ?>
  <div class="alert alert-info">
    <i class="bi bi-info-circle"></i> No activity yet in this group. As members complete modules and solve questions,
    charts will appear here automatically.
  </div>
<?php endif; ?>

<!-- Summary cards -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="card-body">
    <div class="h3 mb-0 text-info"><?= count($members) ?></div><div class="text-muted small">Members</div>
  </div></div></div>
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="card-body">
    <div class="h3 mb-0"><?= array_sum($modulesData) ?></div><div class="text-muted small">Modules completed (group)</div>
  </div></div></div>
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="card-body">
    <div class="h3 mb-0"><?= array_sum($questionsData) ?></div><div class="text-muted small">Questions solved (group)</div>
  </div></div></div>
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="card-body">
    <div class="h3 mb-0"><?= array_sum($quizzesData) ?></div><div class="text-muted small">Quizzes taken (group)</div>
  </div></div></div>
</div>

<div class="row g-4 mb-4">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm h-100"><div class="card-body">
      <h5 class="mb-3">Members comparison</h5>
      <canvas id="compareChart" height="200"></canvas>
    </div></div>
  </div>
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm h-100"><div class="card-body">
      <h5 class="mb-3">Share of modules completed</h5>
      <canvas id="shareChart" height="200"></canvas>
    </div></div>
  </div>
</div>

<div class="card border-0 shadow-sm mb-4"><div class="card-body">
  <h5 class="mb-3">Progress trend over time (cumulative modules)</h5>
  <canvas id="trendChart" height="120"></canvas>
</div></div>

<!-- Completion history -->
<div class="card border-0 shadow-sm mb-4"><div class="card-body">
  <h5 class="mb-3">Module completion history</h5>
  <?php if ($history): ?>
    <div class="table-responsive" style="max-height:420px;overflow:auto">
      <table class="table table-sm table-striped align-middle">
        <thead class="table-light"><tr><th>Member</th><th>Module</th><th>Chapter</th><th>Completed on</th></tr></thead>
        <tbody>
          <?php foreach ($history as $h): ?>
            <tr>
              <td><?= e($h['name']) ?></td>
              <td><?= e($h['module']) ?></td>
              <td class="text-muted small"><?= e($h['chapter']) ?></td>
              <td class="small"><?= e(date('M j, Y g:i A', strtotime($h['completed_at']))) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-muted mb-0">No modules completed by group members yet.</p>
  <?php endif; ?>
</div></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
  const labels = <?= json_encode($labels) ?>;
  const modules = <?= json_encode($modulesData) ?>;
  const questions = <?= json_encode($questionsData) ?>;
  const palette = <?= json_encode($palette) ?>;

  if (window.Chart && labels.length) {
    new Chart(document.getElementById('compareChart'), {
      type: 'bar',
      data: { labels, datasets: [
        { label: 'Modules completed', data: modules, backgroundColor: '#0dcaf0' },
        { label: 'Questions solved', data: questions, backgroundColor: '#fd7e14' },
      ]},
      options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });

    const shareTotal = modules.reduce((a, b) => a + b, 0);
    new Chart(document.getElementById('shareChart'), {
      type: 'doughnut',
      data: { labels, datasets: [{ data: shareTotal ? modules : labels.map(() => 1), backgroundColor: palette }] },
      options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('trendChart'), {
      type: 'line',
      data: { labels: <?= json_encode($trend['dates']) ?>, datasets: <?= json_encode($trendDatasets) ?> },
      options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
  }
})();
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
