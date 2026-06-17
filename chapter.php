<?php
require_once __DIR__ . '/includes/auth.php';

$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare('SELECT * FROM chapters WHERE slug = ?');
$stmt->execute([$slug]);
$chapter = $stmt->fetch();
if (!$chapter) {
    http_response_code(404);
    require __DIR__ . '/partials/header.php';
    echo '<div class="alert alert-danger">Chapter not found.</div>';
    require __DIR__ . '/partials/footer.php';
    exit;
}
$cid = (int) $chapter['id'];

$stmt = db()->prepare('SELECT * FROM topics WHERE chapter_id = ? ORDER BY sort_order, id');
$stmt->execute([$cid]);
$topics = $stmt->fetchAll();

$uid       = current_user_id();
$completed = $uid ? completed_topic_ids($uid) : [];
$progress  = $uid ? chapter_progress($uid, $cid) : ['done' => 0, 'total' => count($topics), 'percent' => 0];

$iqCount = (int) db()->query("SELECT COUNT(*) FROM interview_questions WHERE chapter_id = {$cid}")->fetchColumn();
$pbCount = (int) db()->query("SELECT COUNT(*) FROM practice_problems WHERE chapter_id = {$cid}")->fetchColumn();
$quiz    = db()->query("SELECT * FROM quizzes WHERE chapter_id = {$cid} LIMIT 1")->fetch();

$pageTitle = $chapter['title'];
require __DIR__ . '/partials/header.php';
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('roadmap.php') ?>">Roadmap</a></li>
    <li class="breadcrumb-item active"><?= e($chapter['title']) ?></li>
  </ol>
</nav>

<div class="d-flex align-items-center mb-2">
  <i class="bi <?= e($chapter['icon'] ?: 'bi-journal-code') ?> fs-1 text-info me-3"></i>
  <div>
    <span class="badge level-<?= e($chapter['level']) ?> mb-1"><?= e(LEVELS[$chapter['level']] ?? $chapter['level']) ?></span>
    <h2 class="mb-0"><?= e($chapter['title']) ?></h2>
  </div>
</div>
<p class="text-muted"><?= e($chapter['description']) ?></p>

<?php if ($uid): ?>
<div class="card border-0 shadow-sm mb-4"><div class="card-body">
  <div class="d-flex justify-content-between mb-1">
    <strong>Chapter progress</strong><span><?= $progress['done'] ?>/<?= $progress['total'] ?> topics (<?= $progress['percent'] ?>%)</span>
  </div>
  <div class="progress" style="height:10px">
    <div class="progress-bar bg-<?= $progress['percent'] === 100 ? 'success' : 'info' ?>" style="width:<?= $progress['percent'] ?>%"></div>
  </div>
</div></div>
<?php endif; ?>

<div class="row g-3 mb-4">
  <div class="col-md-4"><a href="<?= url('interview.php?chapter=' . urlencode($slug)) ?>" class="btn btn-outline-dark w-100"><i class="bi bi-briefcase"></i> Interview Q&amp;A (<?= $iqCount ?>)</a></div>
  <div class="col-md-4"><a href="<?= url('practice.php?chapter=' . urlencode($slug)) ?>" class="btn btn-outline-dark w-100"><i class="bi bi-code-square"></i> Practice (<?= $pbCount ?>)</a></div>
  <div class="col-md-4"><?php if ($quiz): ?><a href="<?= url('quiz.php?id=' . (int) $quiz['id']) ?>" class="btn btn-outline-dark w-100"><i class="bi bi-patch-question"></i> Take Quiz</a><?php else: ?><button class="btn btn-outline-secondary w-100" disabled>No quiz</button><?php endif; ?></div>
</div>

<h4 class="mb-3">Topics</h4>
<div class="list-group shadow-sm">
  <?php foreach ($topics as $i => $t):
      $tid    = (int) $t['id'];
      $isDone = in_array($tid, $completed, true);
  ?>
  <a href="<?= url('topic.php?slug=' . urlencode($t['slug'])) ?>" class="list-group-item list-group-item-action d-flex align-items-center">
    <span class="badge bg-light text-dark me-3"><?= $i + 1 ?></span>
    <div class="flex-grow-1">
      <div class="fw-semibold"><?= e($t['title']) ?></div>
      <div class="small text-muted"><?= e($t['summary']) ?></div>
    </div>
    <?php if ($isDone): ?>
      <span class="badge bg-success"><i class="bi bi-check-lg"></i> Done</span>
    <?php else: ?>
      <i class="bi bi-chevron-right text-muted"></i>
    <?php endif; ?>
  </a>
  <?php endforeach; ?>
  <?php if (!$topics): ?><div class="list-group-item text-muted">No topics in this chapter yet.</div><?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
