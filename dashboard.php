<?php
require_once __DIR__ . '/includes/auth.php';
require_login();

$uid  = current_user_id();
$user = current_user();
record_activity($uid);

$overall = overall_progress($uid);
$streak  = current_streak($uid);
$best    = longest_streak($uid);

$levelStats = [];
foreach (LEVELS as $key => $label) {
    $levelStats[$key] = ['label' => $label] + level_progress($uid, $key);
}

// Achievements (earned + locked).
$stmt = db()->prepare('SELECT achievement_id FROM user_achievements WHERE user_id = ?');
$stmt->execute([$uid]);
$earnedIds = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
$allAch = db()->query('SELECT * FROM achievements ORDER BY id')->fetchAll();

// Continue learning: next not-completed topic by curriculum order.
$nextStmt = db()->prepare(
    'SELECT t.title, t.slug, c.title AS chapter_title
     FROM topics t JOIN chapters c ON c.id = t.chapter_id
     WHERE t.id NOT IN (SELECT topic_id FROM user_progress WHERE user_id = ? AND status="completed")
     ORDER BY FIELD(c.level,"beginner","intermediate","advanced"), c.sort_order, t.sort_order, t.id
     LIMIT 1'
);
$nextStmt->execute([$uid]);
$nextTopic = $nextStmt->fetch();

// Recent activity.
$recent = db()->prepare(
    'SELECT t.title, t.slug, up.completed_at
     FROM user_progress up JOIN topics t ON t.id = up.topic_id
     WHERE up.user_id = ? AND up.status="completed" ORDER BY up.completed_at DESC LIMIT 6'
);
$recent->execute([$uid]);
$recentTopics = $recent->fetchAll();

$quizCount   = (int) db()->query("SELECT COUNT(*) FROM user_quiz_attempts WHERE user_id = {$uid}")->fetchColumn();
$solvedCount = (int) db()->query("SELECT COUNT(*) FROM user_problem_solved WHERE user_id = {$uid}")->fetchColumn();

$pageTitle = 'Dashboard';
require __DIR__ . '/partials/header.php';
?>
<h2 class="mb-4">Welcome back, <?= e($user['name']) ?> 👋</h2>

<div class="row g-3 mb-4">
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="card-body">
    <div class="display-6 fw-bold text-info"><?= $overall['percent'] ?>%</div><div class="text-muted">Overall progress</div>
  </div></div></div>
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="card-body">
    <div class="display-6 fw-bold"><i class="bi bi-fire streak-flame"></i> <?= $streak ?></div><div class="text-muted">Day streak</div>
  </div></div></div>
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="card-body">
    <div class="display-6 fw-bold"><?= $overall['done'] ?></div><div class="text-muted">Modules completed</div>
  </div></div></div>
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="card-body">
    <div class="display-6 fw-bold"><?= $solvedCount ?></div><div class="text-muted">Questions solved</div>
  </div></div></div>
</div>
<div class="text-end mb-4"><span class="text-muted small">Quizzes taken: <strong><?= $quizCount ?></strong></span></div>

<?php if ($nextTopic): ?>
<div class="card border-0 shadow-sm mb-4 bg-info-subtle"><div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div><div class="text-muted small">Continue learning</div><h5 class="mb-0"><?= e($nextTopic['title']) ?> <span class="text-muted">&middot; <?= e($nextTopic['chapter_title']) ?></span></h5></div>
  <a href="<?= url('topic.php?slug=' . urlencode($nextTopic['slug'])) ?>" class="btn btn-info">Resume <i class="bi bi-arrow-right"></i></a>
</div></div>
<?php endif; ?>

<div class="row">
  <div class="col-lg-7 mb-4">
    <div class="card border-0 shadow-sm h-100"><div class="card-body">
      <h5 class="mb-3">Progress by level</h5>
      <?php foreach ($levelStats as $key => $st): ?>
        <div class="d-flex justify-content-between mb-1">
          <span><span class="badge level-<?= $key ?>"><?= e($st['label']) ?></span></span>
          <span class="text-muted small"><?= $st['done'] ?>/<?= $st['total'] ?> (<?= $st['percent'] ?>%)</span>
        </div>
        <div class="progress mb-3" style="height:8px"><div class="progress-bar bg-<?= $st['percent'] === 100 ? 'success' : 'info' ?>" style="width:<?= $st['percent'] ?>%"></div></div>
      <?php endforeach; ?>
      <div class="small text-muted">Longest streak: <strong><?= $best ?></strong> days</div>
    </div></div>
  </div>

  <div class="col-lg-5 mb-4">
    <div class="card border-0 shadow-sm h-100"><div class="card-body">
      <h5 class="mb-3">Recently completed</h5>
      <?php if ($recentTopics): ?>
        <ul class="list-unstyled mb-0">
          <?php foreach ($recentTopics as $rt): ?>
            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-1"></i>
              <a href="<?= url('topic.php?slug=' . urlencode($rt['slug'])) ?>" class="text-decoration-none"><?= e($rt['title']) ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="text-muted mb-0">Nothing yet — start with the <a href="<?= url('roadmap.php') ?>">roadmap</a>.</p>
      <?php endif; ?>
    </div></div>
  </div>
</div>

<div class="card border-0 shadow-sm mb-4"><div class="card-body">
  <h5 class="mb-3"><i class="bi bi-trophy"></i> Achievements</h5>
  <div class="row g-3">
    <?php foreach ($allAch as $a): $got = in_array((int) $a['id'], $earnedIds, true); ?>
      <div class="col-6 col-md-3 col-lg-2 text-center">
        <div class="p-3 rounded <?= $got ? 'bg-warning-subtle' : 'bg-light opacity-50' ?>">
          <i class="bi <?= e($a['icon'] ?: 'bi-award') ?> fs-2 <?= $got ? 'text-warning' : 'text-muted' ?>"></i>
          <div class="small fw-semibold mt-1"><?= e($a['title']) ?></div>
          <div class="small text-muted" style="font-size:.72rem"><?= e($a['description']) ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div></div>
<?php require __DIR__ . '/partials/footer.php'; ?>
