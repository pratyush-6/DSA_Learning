<?php
/**
 * Shared admin layout helpers. Call admin_head($title) at the top of a page
 * (after require_admin) and admin_foot() at the end.
 */
require_once __DIR__ . '/../includes/auth.php';
require_admin();

function admin_flash(string $msg = null): ?string
{
    if ($msg !== null) {
        $_SESSION['admin_flash'] = $msg;
        return null;
    }
    $f = $_SESSION['admin_flash'] ?? null;
    unset($_SESSION['admin_flash']);
    return $f;
}

function admin_head(string $title): void
{
    $flash = admin_flash();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> · Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
  <div class="container-xl">
    <a class="navbar-brand" href="<?= url('admin/index.php') ?>"><i class="bi bi-tools"></i> DSA Admin</a>
    <div>
      <a class="btn btn-sm btn-outline-light" href="<?= url('admin/chapters.php') ?>">Chapters</a>
      <a class="btn btn-sm btn-outline-light" href="<?= url('index.php') ?>">View site</a>
    </div>
  </div>
</nav>
<div class="container-xl pb-5">
<?php if ($flash): ?><div class="alert alert-success"><?= e($flash) ?></div><?php endif; ?>
<?php
}

function admin_foot(): void
{
    ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}
