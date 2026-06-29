<?php
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$errors = [];
$name = $email = '';
$preferred = 'php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();
    $name      = trim($_POST['name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm'] ?? '';
    $preferred = $_POST['preferred_language'] ?? 'php';

    if ($name === '')                                  $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))    $errors[] = 'A valid email is required.';
    if (strlen($password) < 6)                         $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm)                        $errors[] = 'Passwords do not match.';
    if (!isset(LANGUAGES[$preferred]))                 $preferred = 'php';

    if (!$errors) {
        $stmt = db()->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'An account with that email already exists.';
        } else {
            $stmt = db()->prepare(
                'INSERT INTO users (name, email, password_hash, preferred_language) VALUES (?,?,?,?)'
            );
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $preferred]);
            login_user((int) db()->lastInsertId());
            redirect('dashboard.php');
        }
    }
}

$pageTitle = 'Sign up';
require __DIR__ . '/partials/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card lift mt-4">
      <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
          <span class="d-inline-grid bg-grad mb-2" style="width:48px;height:48px;border-radius:14px;place-items:center;color:#fff;font-size:1.4rem"><i class="bi bi-diagram-3-fill"></i></span>
          <h3 class="mb-1">Create your account</h3>
          <p class="text-muted small mb-0">Start learning DSA in your language.</p>
        </div>
        <?php if ($errors): ?>
          <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form method="post" novalidate>
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label">Full name</label>
            <input type="text" name="name" class="form-control" value="<?= e($name) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= e($email) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Preferred language</label>
            <select name="preferred_language" class="form-select">
              <?php foreach (LANGUAGES as $k => $label): ?>
                <option value="<?= $k ?>" <?= $preferred === $k ? 'selected' : '' ?>><?= e($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col mb-3">
              <label class="form-label">Confirm</label>
              <input type="password" name="confirm" class="form-control" required>
            </div>
          </div>
          <button class="btn btn-info w-100">Create account</button>
        </form>
        <p class="text-center mt-3 mb-0">Already have an account? <a href="<?= url('login.php') ?>">Log in</a></p>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
