<?php
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        login_user((int) $user['id']);
        $dest = $_SESSION['redirect_after_login'] ?? null;
        unset($_SESSION['redirect_after_login']);
        redirect($dest ?: url('dashboard.php'));
    }
    $error = 'Invalid email or password.';
}

$pageTitle = 'Login';
require __DIR__ . '/partials/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h3 class="mb-3">Welcome back</h3>
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <form method="post">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= e($email) ?>" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button class="btn btn-info w-100">Log in</button>
        </form>
        <p class="text-center mt-3 mb-0">No account? <a href="<?= url('register.php') ?>">Sign up</a></p>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
