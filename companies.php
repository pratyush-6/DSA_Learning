<?php
require_once __DIR__ . '/includes/auth.php';

$companySlug = $_GET['company'] ?? '';

// Companies that actually have tagged questions.
$companies = db()->query(
    'SELECT co.*, COUNT(iqc.question_id) AS q_count
     FROM companies co
     LEFT JOIN interview_question_company iqc ON iqc.company_id = co.id
     GROUP BY co.id HAVING q_count > 0 ORDER BY q_count DESC, co.name'
)->fetchAll();

$selected = null;
$questions = [];
foreach ($companies as $co) {
    if ($co['slug'] === $companySlug) { $selected = $co; }
}
if (!$selected && $companies) { $selected = $companies[0]; }

if ($selected) {
    $stmt = db()->prepare(
        'SELECT iq.*, c.title AS chapter_title, c.slug AS chapter_slug
         FROM interview_questions iq
         JOIN interview_question_company iqc ON iqc.question_id = iq.id
         JOIN chapters c ON c.id = iq.chapter_id
         WHERE iqc.company_id = ?
         ORDER BY iq.difficulty, iq.id'
    );
    $stmt->execute([(int) $selected['id']]);
    $questions = $stmt->fetchAll();
}

$pageTitle = 'Company-wise Questions';
require __DIR__ . '/partials/header.php';
?>
<h2 class="mb-3"><i class="bi bi-building"></i> Company-wise Problem Collections</h2>
<div class="row">
  <div class="col-md-3 mb-3">
    <div class="list-group shadow-sm">
      <?php foreach ($companies as $co): ?>
        <a href="<?= url('companies.php?company=' . urlencode($co['slug'])) ?>"
           class="list-group-item list-group-item-action d-flex justify-content-between <?= $selected && $selected['id'] === $co['id'] ? 'active' : '' ?>">
          <?= e($co['name']) ?><span class="badge bg-secondary rounded-pill"><?= (int) $co['q_count'] ?></span>
        </a>
      <?php endforeach; ?>
      <?php if (!$companies): ?><div class="list-group-item text-muted">No tagged questions yet.</div><?php endif; ?>
    </div>
  </div>
  <div class="col-md-9">
    <?php if ($selected): ?>
      <h4 class="mb-3"><?= e($selected['name']) ?> &mdash; <?= count($questions) ?> questions</h4>
      <div class="accordion shadow-sm" id="coAccordion">
        <?php foreach ($questions as $q): $qid = (int) $q['id']; ?>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#co<?= $qid ?>">
                <span class="badge bg-light difficulty-<?= e($q['difficulty']) ?> border me-2 text-uppercase"><?= e($q['difficulty']) ?></span>
                <span class="flex-grow-1"><?= e($q['question']) ?></span>
              </button>
            </h2>
            <div id="co<?= $qid ?>" class="accordion-collapse collapse" data-bs-parent="#coAccordion">
              <div class="accordion-body lesson-content">
                <div class="small text-muted mb-2">Topic: <a href="<?= url('chapter.php?slug=' . urlencode($q['chapter_slug'])) ?>"><?= e($q['chapter_title']) ?></a></div>
                <?= render_markdown($q['answer_md']) ?: '<em class="text-muted">Answer coming soon.</em>' ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No company collections available yet.</div>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
