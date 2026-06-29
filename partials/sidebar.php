<?php
/**
 * Curriculum sidebar showing the chapter -> topic tree with completion ✓.
 * Expects: $currentChapterId (int|null), $currentTopicId (int|null).
 *
 * NOTE: this file is require'd into the including page's scope, so every local
 * variable here must use an "sb_" prefix to avoid clobbering the caller's
 * variables (e.g. topic.php's $tid / $isDone used by the mark-complete button).
 */
$currentChapterId = $currentChapterId ?? null;
$currentTopicId   = $currentTopicId ?? null;

$sb_uid       = current_user_id();
$sb_completed = $sb_uid ? completed_topic_ids($sb_uid) : [];

$sb_chapters = db()->query(
    'SELECT id, title, slug, level FROM chapters ORDER BY FIELD(level,"beginner","intermediate","advanced"), sort_order, id'
)->fetchAll();

$sb_topicsByChapter = [];
foreach (db()->query('SELECT id, chapter_id, title, slug FROM topics ORDER BY sort_order, id') as $sb_row) {
    $sb_topicsByChapter[(int) $sb_row['chapter_id']][] = $sb_row;
}
?>
<div class="curriculum-sidebar">
  <div class="accordion" id="curriculumAccordion">
    <?php foreach ($sb_chapters as $sb_ch):
        $sb_cid    = (int) $sb_ch['id'];
        $sb_topics = $sb_topicsByChapter[$sb_cid] ?? [];
        $sb_isOpen = $sb_cid === $currentChapterId;
        $sb_doneCnt = 0;
        foreach ($sb_topics as $sb_t) {
            if (in_array((int) $sb_t['id'], $sb_completed, true)) { $sb_doneCnt++; }
        }
        $sb_pct = $sb_topics ? (int) round($sb_doneCnt / count($sb_topics) * 100) : 0;
    ?>
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button <?= $sb_isOpen ? '' : 'collapsed' ?> py-2 small"
                type="button" data-bs-toggle="collapse" data-bs-target="#chap<?= $sb_cid ?>">
          <span class="flex-grow-1 text-truncate"><?= e($sb_ch['title']) ?></span>
          <span class="badge bg-<?= $sb_pct === 100 ? 'success' : 'secondary' ?> ms-2"><?= $sb_pct ?>%</span>
        </button>
      </h2>
      <div id="chap<?= $sb_cid ?>" class="accordion-collapse collapse <?= $sb_isOpen ? 'show' : '' ?>"
           data-bs-parent="#curriculumAccordion">
        <div class="accordion-body p-0">
          <ul class="list-group list-group-flush">
            <?php foreach ($sb_topics as $sb_t):
                $sb_topicId = (int) $sb_t['id'];
                $sb_itemDone = in_array($sb_topicId, $sb_completed, true);
                $sb_active = $sb_topicId === $currentTopicId;
            ?>
            <li class="list-group-item py-1 px-3 small <?= $sb_active ? 'active' : '' ?>">
              <a class="text-decoration-none d-flex align-items-center <?= $sb_active ? 'text-white' : 'text-body' ?>"
                 href="<?= url('topic.php?slug=' . urlencode($sb_t['slug'])) ?>">
                <?php if ($sb_itemDone): ?>
                  <i class="bi bi-check-circle-fill text-success me-2"></i>
                <?php else: ?>
                  <i class="bi bi-circle me-2 text-muted"></i>
                <?php endif; ?>
                <span class="text-truncate"><?= e($sb_t['title']) ?></span>
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
