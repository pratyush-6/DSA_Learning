<?php
require_once __DIR__ . '/_layout.php';

$stats = [
    'Chapters'   => (int) db()->query('SELECT COUNT(*) FROM chapters')->fetchColumn(),
    'Topics'     => (int) db()->query('SELECT COUNT(*) FROM topics')->fetchColumn(),
    'Code snippets' => (int) db()->query('SELECT COUNT(*) FROM code_snippets')->fetchColumn(),
    'Interview Qs' => (int) db()->query('SELECT COUNT(*) FROM interview_questions')->fetchColumn(),
    'Problems'   => (int) db()->query('SELECT COUNT(*) FROM practice_problems')->fetchColumn(),
    'Quiz Qs'    => (int) db()->query('SELECT COUNT(*) FROM quiz_questions')->fetchColumn(),
    'Users'      => (int) db()->query('SELECT COUNT(*) FROM users')->fetchColumn(),
];

admin_head('Dashboard');
?>
<h3 class="mb-4">Content Overview</h3>
<div class="row g-3 mb-4">
  <?php foreach ($stats as $label => $value): ?>
    <div class="col-6 col-md-3"><div class="card text-center shadow-sm"><div class="card-body">
      <div class="h3 mb-0"><?= $value ?></div><div class="text-muted small"><?= e($label) ?></div>
    </div></div></div>
  <?php endforeach; ?>
</div>
<a href="<?= url('admin/chapters.php') ?>" class="btn btn-primary"><i class="bi bi-collection"></i> Manage Chapters &amp; Content</a>
<?php admin_foot(); ?>
