<?php
/** Top navigation bar. */
$navUser = current_user();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
  <div class="container-xl">
    <a class="navbar-brand fw-bold" href="<?= url('index.php') ?>">
      <i class="bi bi-diagram-3-fill text-info"></i> DSA<span class="text-info">Learn</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?= url('roadmap.php') ?>"><i class="bi bi-map"></i> Roadmap</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= url('practice.php') ?>"><i class="bi bi-code-square"></i> Practice</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= url('companies.php') ?>"><i class="bi bi-building"></i> Companies</a></li>
        <?php if ($navUser): ?>
          <li class="nav-item"><a class="nav-link" href="<?= url('groups.php') ?>"><i class="bi bi-people"></i> Group</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= url('bookmarks.php') ?>"><i class="bi bi-bookmark-star"></i> Bookmarks</a></li>
        <?php endif; ?>
      </ul>
      <form class="d-flex me-2" role="search" action="<?= url('search.php') ?>" method="get">
        <input class="form-control form-control-sm" type="search" name="q" placeholder="Search topics..."
               value="<?= e($_GET['q'] ?? '') ?>">
      </form>
      <ul class="navbar-nav">
        <?php if ($navUser): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i> <?= e($navUser['name']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?= url('dashboard.php') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
              <li><a class="dropdown-item" href="<?= url('profile.php') ?>"><i class="bi bi-gear"></i> Profile &amp; Language</a></li>
              <?php if (is_admin()): ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= url('admin/index.php') ?>"><i class="bi bi-tools"></i> Admin</a></li>
              <?php endif; ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?= url('logout.php') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= url('login.php') ?>">Login</a></li>
          <li class="nav-item"><a class="btn btn-info btn-sm ms-2 mt-1" href="<?= url('register.php') ?>">Sign up</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
