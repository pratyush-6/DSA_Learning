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

// Has the current user marked this problem as solved?
$isSolved = false;
if (is_logged_in()) {
    $s = db()->prepare('SELECT 1 FROM user_problem_solved WHERE user_id = ? AND problem_id = ?');
    $s->execute([current_user_id(), (int) $problem['id']]);
    $isSolved = (bool) $s->fetchColumn();
}

// Built-in compiler: test cases + starter code.
$tcStmt = db()->prepare('SELECT * FROM problem_testcases WHERE problem_id = ? ORDER BY sort_order, id');
$tcStmt->execute([(int) $problem['id']]);
$testcases  = $tcStmt->fetchAll();
$hasCompiler = count($testcases) > 0;
$samples    = array_values(array_filter($testcases, fn($t) => (int) $t['is_sample'] === 1));

$starters = [];
if ($hasCompiler) {
    $st = db()->prepare('SELECT language, code FROM problem_starters WHERE problem_id = ?');
    $st->execute([(int) $problem['id']]);
    foreach ($st->fetchAll() as $r) { $starters[$r['language']] = $r['code']; }
    // Prefill with the user's last submission per language if present.
    if (is_logged_in()) {
        $su = db()->prepare('SELECT language, code FROM user_submissions WHERE user_id = ? AND problem_id = ?');
        $su->execute([current_user_id(), (int) $problem['id']]);
        foreach ($su->fetchAll() as $r) { $starters[$r['language']] = $r['code']; }
    }
}
$editorLang = $hasCompiler ? (isset($starters[$prefLang]) ? $prefLang : array_key_first($starters)) : $prefLang;

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

<div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
  <h2 class="mb-0"><?= e($problem['title']) ?></h2>
  <span class="badge bg-light difficulty-<?= e($problem['difficulty']) ?> border text-uppercase"><?= e($problem['difficulty']) ?></span>
  <?php if (is_logged_in() && $hasCompiler): ?>
    <span id="solved-badge" class="badge ms-auto <?= $isSolved ? 'bg-success' : 'bg-secondary' ?>">
      <i class="bi bi-<?= $isSolved ? 'check-circle-fill' : 'hourglass-split' ?>"></i>
      <?= $isSolved ? 'Completed' : 'Pass all tests to complete' ?>
    </span>
  <?php elseif (is_logged_in()): ?>
    <button id="solve-btn" class="btn btn-sm <?= $isSolved ? 'btn-success' : 'btn-outline-success' ?> ms-auto"
            data-problem-id="<?= (int) $problem['id'] ?>" data-solved="<?= $isSolved ? '1' : '0' ?>">
      <i class="bi bi-<?= $isSolved ? 'check-circle-fill' : 'circle' ?>"></i> <?= $isSolved ? 'Solved' : 'Mark as solved' ?>
    </button>
  <?php endif; ?>
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

<?php if ($hasCompiler): ?>
  <?php if (!is_logged_in()): ?>
    <div class="alert alert-info mt-4"><a href="<?= url('login.php') ?>">Log in</a> to write, run, and test your code in the built-in compiler.</div>
  <?php else: ?>
  <div class="card mt-4" id="compiler"><div class="card-body">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <h5 class="mb-0"><i class="bi bi-terminal"></i> Code Editor</h5>
      <div class="nav nav-tabs border-0" id="lang-tabs" role="tablist">
        <?php foreach ($langOrder as $lang): if (!isset($starters[$lang])) continue; ?>
          <button class="nav-link <?= $lang === $editorLang ? 'active' : '' ?>" type="button" data-lang="<?= $lang ?>"><?= e(lang_label($lang)) ?></button>
        <?php endforeach; ?>
      </div>
    </div>

    <textarea id="code-editor"><?= e($starters[$editorLang] ?? '') ?></textarea>

    <div class="row g-3 mt-1">
      <div class="col-md-5">
        <label class="form-label small mb-1">Custom input (stdin)</label>
        <textarea id="custom-stdin" class="form-control font-monospace" rows="3"><?= e($samples[0]['stdin'] ?? '') ?></textarea>
      </div>
      <div class="col-md-7">
        <label class="form-label small mb-1">Output</label>
        <pre id="run-output" class="surface-2 p-2 rounded mb-0" style="min-height:86px;white-space:pre-wrap"></pre>
      </div>
    </div>

    <div class="d-flex gap-2 mt-3 align-items-center">
      <button id="run-btn" class="btn btn-outline-primary"><i class="bi bi-play-fill"></i> Run</button>
      <button id="submit-btn" class="btn btn-primary"><i class="bi bi-cloud-check"></i> Submit</button>
      <button id="reset-btn" class="btn btn-link ms-auto" type="button">Reset code</button>
    </div>

    <div id="submit-results" class="mt-3"></div>
  </div></div>

  <div class="card mt-3"><div class="card-body">
    <h6 class="mb-3">Sample test cases</h6>
    <?php foreach ($samples as $tc): ?>
      <div class="row g-2 mb-2">
        <div class="col-md-6"><div class="small text-muted mb-1">Input</div><pre class="surface-2 p-2 rounded mb-0"><?= e($tc['stdin']) ?></pre></div>
        <div class="col-md-6"><div class="small text-muted mb-1">Expected output</div><pre class="surface-2 p-2 rounded mb-0"><?= e($tc['expected_output']) ?></pre></div>
      </div>
    <?php endforeach; ?>
    <div class="small text-muted mt-2"><i class="bi bi-lock"></i> Additional hidden test cases run on submit. The exercise is marked complete only when <strong>all</strong> tests pass.</div>
  </div></div>

  <script>
    window.__compiler = {
      problemId: <?= (int) $problem['id'] ?>,
      starters: <?= json_encode($starters) ?>,
      activeLang: <?= json_encode($editorLang) ?>,
      solved: <?= $isSolved ? 'true' : 'false' ?>
    };
  </script>
  <?php endif; ?>
<?php endif; ?>

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

<?php if ($hasCompiler && is_logged_in()): ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/material-darker.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/clike/clike.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/python/python.min.js"></script>
  <script src="<?= asset('js/compiler.js') ?>"></script>
<?php endif; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
