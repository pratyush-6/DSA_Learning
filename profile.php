<?php
require_once __DIR__ . '/includes/auth.php';
require_login();

$user    = current_user();
$success = '';
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();
    $name      = trim($_POST['name'] ?? '');
    $preferred = $_POST['preferred_language'] ?? 'php';
    $password  = $_POST['password'] ?? '';

    if ($name === '')                  $errors[] = 'Name is required.';
    if (!isset(LANGUAGES[$preferred])) $preferred = 'php';

    if (!$errors) {
        db()->prepare('UPDATE users SET name = ?, preferred_language = ? WHERE id = ?')
            ->execute([$name, $preferred, $user['id']]);
        if ($password !== '') {
            if (strlen($password) < 6) {
                $errors[] = 'New password must be at least 6 characters.';
            } else {
                db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
                    ->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
            }
        }
        if (!$errors) {
            $success = 'Profile updated.';
            // Refresh cached user values used on the page.
            $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$user['id']]);
            $user = $stmt->fetch();
        }
    }
}

$pageTitle = 'Profile';
require __DIR__ . '/partials/header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-6">
    <h3 class="mb-3">Profile &amp; Settings</h3>
    <?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
    <?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <div class="card shadow-sm"><div class="card-body p-4">
      <form method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" value="<?= e($user['email']) ?>" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label">Preferred programming language</label>
          <select name="preferred_language" class="form-select">
            <?php foreach (LANGUAGES as $k => $label): ?>
              <option value="<?= $k ?>" <?= $user['preferred_language'] === $k ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
          <div class="form-text">Code examples default to this language.</div>
        </div>
        <div class="mb-3">
          <label class="form-label">New password <span class="text-muted">(leave blank to keep current)</span></label>
          <input type="password" name="password" class="form-control">
        </div>
        <button class="btn btn-info">Save changes</button>
      </form>
    </div></div>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
