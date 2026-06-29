<?php
/** Top navigation bar. */
$navUser = current_user();
$curr    = basename($_SERVER['PHP_SELF'] ?? '');
$isActive = fn(string $f) => $curr === $f ? 'active' : '';

// Recent achievements act as notifications.
$notes = [];
if ($navUser) {
    $st = db()->prepare(
        'SELECT a.title, a.description, a.icon, ua.earned_at
         FROM user_achievements ua JOIN achievements a ON a.id = ua.achievement_id
         WHERE ua.user_id = ? ORDER BY ua.earned_at DESC LIMIT 6'
    );
    $st->execute([(int) $navUser['id']]);
    $notes = $st->fetchAll();
}
?>
<nav class="navbar navbar-expand-lg app-nav sticky-top">
  <div class="container-xl">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= url('index.php') ?>">
      <span class="d-inline-grid bg-grad" style="width:32px;height:32px;border-radius:9px;place-items:center;color:#fff">
        <i class="bi bi-diagram-3-fill"></i>
      </span>
      <span>DSA<span class="brand-mark">Learn</span></span>
    </a>

    <div class="d-flex align-items-center gap-2 order-lg-last">
      <!-- Theme toggle -->
      <button class="icon-btn" id="theme-toggle" type="button" aria-label="Toggle dark mode" title="Toggle theme">
        <i class="bi bi-moon-stars" data-theme-icon></i>
      </button>

      <?php if ($navUser): ?>
        <!-- Notifications -->
        <div class="dropdown">
          <button class="icon-btn position-relative" type="button" data-bs-toggle="dropdown" aria-label="Notifications">
            <i class="bi bi-bell"></i>
            <?php if ($notes): ?><span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle"><span class="visually-hidden">new</span></span><?php endif; ?>
          </button>
          <div class="dropdown-menu dropdown-menu-end p-2" style="min-width:280px">
            <h6 class="dropdown-header">Achievements</h6>
            <?php if ($notes): foreach ($notes as $n): ?>
              <div class="d-flex gap-2 px-2 py-2 align-items-start">
                <i class="bi <?= e($n['icon'] ?: 'bi-award') ?> text-warning fs-5"></i>
                <div>
                  <div class="fw-semibold small"><?= e($n['title']) ?></div>
                  <div class="text-muted" style="font-size:.75rem"><?= e($n['description']) ?></div>
                </div>
              </div>
            <?php endforeach; else: ?>
              <div class="text-muted small px-2 py-3 text-center">No achievements yet — start learning!</div>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>

      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <i class="bi bi-list fs-3"></i>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-lg-auto mt-2 mt-lg-0">
        <li class="nav-item"><a class="nav-link <?= $isActive('roadmap.php') ?>" href="<?= url('roadmap.php') ?>"><i class="bi bi-map"></i> Roadmap</a></li>
        <li class="nav-item"><a class="nav-link <?= $isActive('practice.php') ?>" href="<?= url('practice.php') ?>"><i class="bi bi-code-square"></i> Practice</a></li>
        <li class="nav-item"><a class="nav-link <?= $isActive('leaderboard.php') ?>" href="<?= url('leaderboard.php') ?>"><i class="bi bi-trophy"></i> Leaderboard</a></li>
        <li class="nav-item"><a class="nav-link <?= $isActive('companies.php') ?>" href="<?= url('companies.php') ?>"><i class="bi bi-building"></i> Companies</a></li>
        <?php if ($navUser): ?>
          <li class="nav-item"><a class="nav-link <?= $isActive('groups.php') ?>" href="<?= url('groups.php') ?>"><i class="bi bi-people"></i> Group</a></li>
          <li class="nav-item"><a class="nav-link <?= $isActive('bookmarks.php') ?>" href="<?= url('bookmarks.php') ?>"><i class="bi bi-bookmark-star"></i> Bookmarks</a></li>
        <?php endif; ?>
      </ul>

      <form class="d-flex me-lg-2 my-2 my-lg-0" role="search" action="<?= url('search.php') ?>" method="get">
        <div class="input-group input-group-sm">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input class="form-control" type="search" name="q" placeholder="Search topics…" value="<?= e($_GET['q'] ?? '') ?>" aria-label="Search">
        </div>
      </form>

      <ul class="navbar-nav">
        <?php if ($navUser): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
              <span class="d-inline-grid bg-grad" style="width:30px;height:30px;border-radius:50%;place-items:center;color:#fff;font-weight:700">
                <?= e(strtoupper(substr($navUser['name'], 0, 1))) ?>
              </span>
              <span class="d-none d-lg-inline"><?= e($navUser['name']) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?= url('dashboard.php') ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
              <li><a class="dropdown-item" href="<?= url('profile.php') ?>"><i class="bi bi-gear me-2"></i>Settings</a></li>
              <?php if (is_admin()): ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= url('admin/index.php') ?>"><i class="bi bi-tools me-2"></i>Admin</a></li>
              <?php endif; ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?= url('logout.php') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item d-flex align-items-center gap-2">
            <a class="nav-link" href="<?= url('login.php') ?>">Login</a>
            <a class="btn btn-primary btn-sm" href="<?= url('register.php') ?>">Sign up</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
