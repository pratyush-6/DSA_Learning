<?php
require_once __DIR__ . '/includes/auth.php';

$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare(
    'SELECT p.*, c.title AS chapter_title, c.slug AS chapter_slug
     FROM practice_problems p JOIN chapters c ON c.id = p.chapter_id WHERE p.slug = ?'
);
$stmt->execute([$slug]);
$problem = $stmt->fetch();
if (!$problem) {
    http_response_code(404);
    require __DIR__ . '/partials/header.php';
    echo '<div class="alert alert-danger">Problem not found.</div>';
    require __DIR__ . '/partials/footer.php';
    exit;
}

$solStmt = db()->prepare('SELECT * FROM practice_solutions WHERE problem_id = ?');
$solStmt->execute([(int) $problem['id']]);
$solByLang = [];
foreach ($solStmt->fetchAll() as $s) {
    $solByLang[$s['language']] = $s;
}
$langOrder  = array_keys(LANGUAGES);
$prefLang   = is_logged_in() ? current_language() : 'php';
$activeLang = !empty($solByLang[$prefLang]) ? $prefLang : (array_key_first($solByLang) ?: null);

$pageTitle = $problem['title'];
require __DIR__ . '/partials/header.php';
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('practice.php') ?>">Practice</a></li>
    <li class="breadcrumb-item"><a href="<?= url('chapter.php?slug=' . urlencode($problem['chapter_slug'])) ?>"><?= e($problem['chapter_title']) ?></a></li>
    <li class="breadcrumb-item active"><?= e($problem['title']) ?></li>
  </ol>
</nav>

<div class="d-flex align-items-center gap-2 mb-3">
  <h2 class="mb-0"><?= e($problem['title']) ?></h2>
  <span class="badge bg-light difficulty-<?= e($problem['difficulty']) ?> border text-uppercase"><?= e($problem['difficulty']) ?></span>
</div>

<div class="lesson-content">
  <?= render_markdown($problem['statement_md']) ?>
  <?php if ($problem['examples_md']): ?>
    <h3>Examples</h3>
    <?= render_markdown($problem['examples_md']) ?>
  <?php endif; ?>
  <?php if ($problem['constraints_md']): ?>
    <h3>Constraints</h3>
    <?= render_markdown($problem['constraints_md']) ?>
  <?php endif; ?>
</div>

<?php if ($solByLang): ?>
<div class="mt-4">
  <button class="btn btn-warning" type="button" onclick="document.getElementById('solution-block').classList.toggle('solution-hidden'); this.querySelector('span').textContent = document.getElementById('solution-block').classList.contains('solution-hidden') ? 'Reveal Solution' : 'Hide Solution';">
    <i class="bi bi-eye"></i> <span>Reveal Solution</span>
  </button>
</div>

<div id="solution-block" class="solution-hidden mt-3">
  <ul class="nav nav-tabs" role="tablist">
    <?php foreach ($langOrder as $lang):
        if (empty($solByLang[$lang])) continue; ?>
      <li class="nav-item" role="presentation">
        <button class="nav-link <?= $lang === $activeLang ? 'active' : '' ?>" data-lang-tab="<?= $lang ?>"
                data-bs-toggle="tab" data-bs-target="#sol<?= $lang ?>" type="button" role="tab"><?= e(lang_label($lang)) ?></button>
      </li>
    <?php endforeach; ?>
  </ul>
  <div class="tab-content border border-top-0 rounded-bottom p-3 bg-white">
    <?php foreach ($langOrder as $lang):
        if (empty($solByLang[$lang])) continue;
        $sol = $solByLang[$lang]; ?>
      <div class="tab-pane code-tab-pane fade <?= $lang === $activeLang ? 'show active' : '' ?>" id="sol<?= $lang ?>" role="tabpanel">
        <pre><code class="language-<?= e($lang) ?>"><?= e($sol['code']) ?></code></pre>
        <?php if ($sol['explanation_md']): ?>
          <h6 class="mt-2">Explanation</h6>
          <div class="lesson-content"><?= render_markdown($sol['explanation_md']) ?></div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php else: ?>
  <div class="alert alert-secondary mt-4">Solution coming soon.</div>
<?php endif; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
