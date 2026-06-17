<?php
/**
 * Curriculum sidebar showing the chapter -> topic tree with completion ✓.
 * Expects: $currentChapterId (int|null), $currentTopicId (int|null).
 */
$currentChapterId = $currentChapterId ?? null;
$currentTopicId   = $currentTopicId ?? null;

$uid       = current_user_id();
$completed = $uid ? completed_topic_ids($uid) : [];

$chapters = db()->query(
    'SELECT id, title, slug, level FROM chapters ORDER BY FIELD(level,"beginner","intermediate","advanced"), sort_order, id'
)->fetchAll();

$topicsByChapter = [];
foreach (db()->query('SELECT id, chapter_id, title, slug FROM topics ORDER BY sort_order, id') as $t) {
    $topicsByChapter[(int) $t['chapter_id']][] = $t;
}
?>
<div class="curriculum-sidebar">
  <div class="accordion" id="curriculumAccordion">
    <?php foreach ($chapters as $ch):
        $cid     = (int) $ch['id'];
        $topics  = $topicsByChapter[$cid] ?? [];
        $isOpen  = $cid === $currentChapterId;
        $doneCnt = 0;
        foreach ($topics as $t) {
            if (in_array((int) $t['id'], $completed, true)) { $doneCnt++; }
        }
        $pct = $topics ? (int) round($doneCnt / count($topics) * 100) : 0;
    ?>
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button <?= $isOpen ? '' : 'collapsed' ?> py-2 small"
                type="button" data-bs-toggle="collapse" data-bs-target="#chap<?= $cid ?>">
          <span class="flex-grow-1 text-truncate"><?= e($ch['title']) ?></span>
          <span class="badge bg-<?= $pct === 100 ? 'success' : 'secondary' ?> ms-2"><?= $pct ?>%</span>
        </button>
      </h2>
      <div id="chap<?= $cid ?>" class="accordion-collapse collapse <?= $isOpen ? 'show' : '' ?>"
           data-bs-parent="#curriculumAccordion">
        <div class="accordion-body p-0">
          <ul class="list-group list-group-flush">
            <?php foreach ($topics as $t):
                $tid    = (int) $t['id'];
                $isDone = in_array($tid, $completed, true);
                $active = $tid === $currentTopicId;
            ?>
            <li class="list-group-item py-1 px-3 small <?= $active ? 'active' : '' ?>">
              <a class="text-decoration-none d-flex align-items-center <?= $active ? 'text-white' : 'text-body' ?>"
                 href="<?= url('topic.php?slug=' . urlencode($t['slug'])) ?>">
                <?php if ($isDone): ?>
                  <i class="bi bi-check-circle-fill text-success me-2"></i>
                <?php else: ?>
                  <i class="bi bi-circle me-2 text-muted"></i>
                <?php endif; ?>
                <span class="text-truncate"><?= e($t['title']) ?></span>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
