<?php
require_once __DIR__ . '/includes/auth.php';

$chapterCount = (int) db()->query('SELECT COUNT(*) FROM chapters')->fetchColumn();
$topicCount   = (int) db()->query('SELECT COUNT(*) FROM topics')->fetchColumn();
$problemCount = (int) db()->query('SELECT COUNT(*) FROM practice_problems')->fetchColumn();

$pageTitle = 'Learn Data Structures & Algorithms';
require __DIR__ . '/partials/header.php';
?>
<div class="hero p-5 mb-5">
  <div class="row align-items-center">
    <div class="col-lg-7">
      <h1 class="display-5 fw-bold">Master DSA — from Beginner to Job-Ready</h1>
      <p class="lead mt-3">
        A structured roadmap across Beginner, Intermediate, and Advanced levels.
        Learn in <strong>PHP, C++, Java, or Python</strong> with real-world examples,
        interview prep, coding practice, and detailed progress tracking.
      </p>
      <div class="mt-4">
        <a href="<?= url('roadmap.php') ?>" class="btn btn-info btn-lg"><i class="bi bi-map"></i> Explore Roadmap</a>
        <?php if (!is_logged_in()): ?>
          <a href="<?= url('register.php') ?>" class="btn btn-outline-light btn-lg ms-2">Get started free</a>
        <?php else: ?>
          <a href="<?= url('dashboard.php') ?>" class="btn btn-outline-light btn-lg ms-2">My Dashboard</a>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-lg-5 text-center d-none d-lg-block">
      <i class="bi bi-diagram-3" style="font-size:11rem;opacity:.25"></i>
    </div>
  </div>
</div>

<div class="row text-center g-4 mb-5">
  <div class="col-md-4"><div class="card border-0 shadow-sm py-3"><div class="card-body">
    <div class="display-6 fw-bold text-info"><?= $chapterCount ?></div><div class="text-muted">Chapters</div>
  </div></div></div>
  <div class="col-md-4"><div class="card border-0 shadow-sm py-3"><div class="card-body">
    <div class="display-6 fw-bold text-info"><?= $topicCount ?></div><div class="text-muted">Topics</div>
  </div></div></div>
  <div class="col-md-4"><div class="card border-0 shadow-sm py-3"><div class="card-body">
    <div class="display-6 fw-bold text-info"><?= $problemCount ?></div><div class="text-muted">Practice Problems</div>
  </div></div></div>
</div>

<h3 class="mb-4 text-center">Everything you need to crack interviews</h3>
<div class="row g-4">
  <?php
  $features = [
      ['bi-signpost-split', 'Progressive Roadmap', 'Chapter → Topic → Subtopic, ordered easy to advanced so each concept builds on the last.'],
      ['bi-translate', 'Multi-Language', 'Switch every code example between PHP, C++, Java, and Python.'],
      ['bi-lightbulb', 'Real-World Examples', 'See where each data structure & algorithm is actually used.'],
      ['bi-building', 'Interview Prep', 'Company-wise questions, common problems, and best-practice tips.'],
      ['bi-code-square', 'Practice & Quizzes', 'Coding problems with reveal-solutions plus MCQ quizzes per chapter.'],
      ['bi-graph-up-arrow', 'Progress Tracking', 'Completion ✓, chapter %, streaks, and achievements on your dashboard.'],
  ];
  foreach ($features as [$icon, $title, $desc]): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 border-0 shadow-sm"><div class="card-body">
        <i class="bi <?= $icon ?> text-info fs-2"></i>
        <h5 class="mt-2"><?= e($title) ?></h5>
        <p class="text-muted mb-0"><?= e($desc) ?></p>
      </div></div>
    </div>
  <?php endforeach; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
