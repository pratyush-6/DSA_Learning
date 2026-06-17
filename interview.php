<?php
require_once __DIR__ . '/includes/auth.php';

$chapterSlug = $_GET['chapter'] ?? '';
$chapter = null;
if ($chapterSlug !== '') {
    $stmt = db()->prepare('SELECT * FROM chapters WHERE slug = ?');
    $stmt->execute([$chapterSlug]);
    $chapter = $stmt->fetch();
}

$sql = 'SELECT iq.*, c.title AS chapter_title, c.slug AS chapter_slug
        FROM interview_questions iq JOIN chapters c ON c.id = iq.chapter_id';
$params = [];
if ($chapter) { $sql .= ' WHERE iq.chapter_id = ?'; $params[] = (int) $chapter['id']; }
$sql .= ' ORDER BY FIELD(c.level,"beginner","intermediate","advanced"), c.sort_order, iq.sort_order, iq.id';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$questions = $stmt->fetchAll();

// Companies per question for tags.
$companiesByQ = [];
if ($questions) {
    $ids = implode(',', array_map(fn($q) => (int) $q['id'], $questions));
    foreach (db()->query(
        "SELECT iqc.question_id, co.name FROM interview_question_company iqc
         JOIN companies co ON co.id = iqc.company_id WHERE iqc.question_id IN ($ids)"
    ) as $row) {
        $companiesByQ[(int) $row['question_id']][] = $row['name'];
    }
}

$pageTitle = $chapter ? $chapter['title'] . ' — Interview Q&A' : 'Interview Questions';
require __DIR__ . '/partials/header.php';
?>
<h2 class="mb-3"><i class="bi bi-briefcase"></i> Interview Questions<?= $chapter ? ' — ' . e($chapter['title']) : '' ?></h2>
<p class="text-muted">Click a question to reveal a model answer. Tips: explain your approach aloud, state complexity, and discuss trade-offs.</p>

<div class="accordion shadow-sm" id="iqAccordion">
  <?php foreach ($questions as $i => $q): $qid = (int) $q['id']; ?>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#iq<?= $qid ?>">
        <span class="badge bg-<?= $q['type'] === 'coding' ? 'dark' : 'secondary' ?> me-2"><?= ucfirst($q['type']) ?></span>
        <span class="badge bg-light difficulty-<?= e($q['difficulty']) ?> border me-2 text-uppercase"><?= e($q['difficulty']) ?></span>
        <span class="flex-grow-1"><?= e($q['question']) ?></span>
      </button>
    </h2>
    <div id="iq<?= $qid ?>" class="accordion-collapse collapse" data-bs-parent="#iqAccordion">
      <div class="accordion-body lesson-content">
        <?php if (!empty($companiesByQ[$qid])): ?>
          <div class="mb-2"><?php foreach ($companiesByQ[$qid] as $co): ?><span class="badge rounded-pill bg-info-subtle text-dark border me-1"><?= e($co) ?></span><?php endforeach; ?></div>
        <?php endif; ?>
        <?php if (!$chapter): ?><div class="small text-muted mb-2">From: <a href="<?= url('interview.php?chapter=' . urlencode($q['chapter_slug'])) ?>"><?= e($q['chapter_title']) ?></a></div><?php endif; ?>
        <?= render_markdown($q['answer_md']) ?: '<em class="text-muted">Answer coming soon.</em>' ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (!$questions): ?><div class="p-3 text-muted">No interview questions yet.</div><?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
