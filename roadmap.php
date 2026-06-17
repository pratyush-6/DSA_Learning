<?php
require_once __DIR__ . '/includes/auth.php';

$uid       = current_user_id();
$completed = $uid ? completed_topic_ids($uid) : [];

$chapters = db()->query(
    'SELECT c.*, (SELECT COUNT(*) FROM topics t WHERE t.chapter_id = c.id) AS topic_count
     FROM chapters c
     ORDER BY FIELD(level,"beginner","intermediate","advanced"), sort_order, id'
)->fetchAll();

// Map chapter_id -> [topic ids] for progress calculation.
$topicsByChapter = [];
foreach (db()->query('SELECT id, chapter_id FROM topics') as $t) {
    $topicsByChapter[(int) $t['chapter_id']][] = (int) $t['id'];
}

$byLevel = ['beginner' => [], 'intermediate' => [], 'advanced' => []];
foreach ($chapters as $ch) {
    $byLevel[$ch['level']][] = $ch;
}

$pageTitle = 'Learning Roadmap';
require __DIR__ . '/partials/header.php';
?>
<h2 class="mb-1"><i class="bi bi-map"></i> DSA Learning Roadmap</h2>
<p class="text-muted">Follow the path top to bottom. Each chapter builds on the previous one.</p>

<?php foreach (LEVELS as $levelKey => $levelLabel):
    $list = $byLevel[$levelKey] ?? [];
    if (!$list) continue;
?>
  <div class="d-flex align-items-center mt-4 mb-3">
    <span class="badge level-<?= $levelKey ?> fs-6 me-2"><?= e($levelLabel) ?></span>
    <hr class="flex-grow-1">
  </div>
  <div class="row g-3">
    <?php foreach ($list as $i => $ch):
        $cid    = (int) $ch['id'];
        $tids   = $topicsByChapter[$cid] ?? [];
        $done   = count(array_intersect($tids, $completed));
        $total  = count($tids);
        $pct    = $total ? (int) round($done / $total * 100) : 0;
        $number = $i + 1;
    ?>
    <div class="col-md-6 col-lg-4">
      <a href="<?= url('chapter.php?slug=' . urlencode($ch['slug'])) ?>" class="text-decoration-none">
        <div class="card chapter-card h-100 border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-start">
              <i class="bi <?= e($ch['icon'] ?: 'bi-journal-code') ?> fs-3 text-info me-3"></i>
              <div class="flex-grow-1">
                <h5 class="mb-1 text-body"><?= e($ch['title']) ?></h5>
                <p class="text-muted small mb-2"><?= e($ch['description']) ?></p>
              </div>
              <?php if ($pct === 100): ?><i class="bi bi-check-circle-fill text-success fs-5"></i><?php endif; ?>
            </div>
            <div class="d-flex justify-content-between small text-muted mb-1">
              <span><?= $total ?> topics</span><span><?= $done ?>/<?= $total ?> done</span>
            </div>
            <div class="progress" style="height:6px">
              <div class="progress-bar bg-<?= $pct === 100 ? 'success' : 'info' ?>" style="width:<?= $pct ?>%"></div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
<?php endforeach; ?>

<?php if (!$chapters): ?>
  <div class="alert alert-warning mt-4">No content yet. Run <a href="<?= url('setup.php') ?>">setup.php</a> to seed the curriculum.</div>
<?php endif; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
