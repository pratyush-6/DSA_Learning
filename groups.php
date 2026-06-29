<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/groups.php';
require_login();

$uid     = current_user_id();
$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $r = create_group($uid, $_POST['name'] ?? '');
        if ($r['ok']) { $success = 'Group created! Share your join code with friends.'; }
        else { $errors[] = $r['error']; }
    } elseif ($action === 'join') {
        $r = join_group($uid, $_POST['code'] ?? '');
        if ($r['ok']) { $success = 'You joined the group.'; }
        else { $errors[] = $r['error']; }
    } elseif ($action === 'leave') {
        $r = leave_group($uid);
        if ($r['ok']) { $success = 'You left the group.'; }
        else { $errors[] = $r['error']; }
    }
}

$group   = user_group($uid);
$members = $group ? group_member_stats((int) $group['id']) : [];

$pageTitle = 'Group Study';
require __DIR__ . '/partials/header.php';
?>
<h2 class="mb-1"><i class="bi bi-people-fill"></i> Group Study</h2>
<p class="text-muted">Study with friends, compare progress, and stay motivated. You can be in one group at a time.</p>

<?php foreach ($errors as $er): ?><div class="alert alert-danger"><?= e($er) ?></div><?php endforeach; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<?php if ($group): ?>
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
          <h4 class="mb-1"><?= e($group['name']) ?></h4>
          <div class="text-muted">Join code:
            <span class="badge bg-dark fs-6 user-select-all" style="letter-spacing:2px"><?= e($group['join_code']) ?></span>
            <button class="btn btn-sm btn-outline-secondary ms-1" onclick="navigator.clipboard?.writeText('<?= e($group['join_code']) ?>'); this.textContent='Copied!';">Copy</button>
          </div>
          <div class="small text-muted mt-1">You joined <?= e(date('M j, Y', strtotime($group['joined_at']))) ?> · <?= count($members) ?> member(s)</div>
        </div>
        <div class="d-flex gap-2">
          <a href="<?= url('group_dashboard.php') ?>" class="btn btn-info"><i class="bi bi-bar-chart-line"></i> Comparison Dashboard</a>
          <form method="post" onsubmit="return confirm('Leave this group? Your progress is kept, but you will leave the group.');">
            <?= csrf_field() ?><input type="hidden" name="action" value="leave">
            <button class="btn btn-outline-danger"><i class="bi bi-box-arrow-left"></i> Leave</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <h5 class="mb-3">Members</h5>
  <div class="table-responsive">
    <table class="table table-hover bg-white shadow-sm align-middle">
      <thead class="table-light">
        <tr><th>#</th><th>Member</th><th class="text-center">Modules completed</th><th class="text-center">Questions solved</th><th class="text-center">Quizzes</th><th>Joined</th></tr>
      </thead>
      <tbody>
        <?php foreach ($members as $i => $m): ?>
          <tr<?= (int) $m['id'] === $uid ? ' class="table-info"' : '' ?>>
            <td><?= $i + 1 ?></td>
            <td><i class="bi bi-person-circle me-1"></i><?= e($m['name']) ?><?= (int) $m['id'] === $uid ? ' <span class="badge bg-secondary">You</span>' : '' ?></td>
            <td class="text-center fw-semibold"><?= (int) $m['modules_completed'] ?></td>
            <td class="text-center fw-semibold"><?= (int) $m['questions_solved'] ?></td>
            <td class="text-center"><?= (int) $m['quizzes_taken'] ?></td>
            <td class="small text-muted"><?= e(date('M j, Y', strtotime($m['joined_at']))) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

<?php else: ?>
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100"><div class="card-body">
        <h5><i class="bi bi-plus-circle"></i> Create a group</h5>
        <p class="text-muted small">You will get a unique join code to share.</p>
        <form method="post">
          <?= csrf_field() ?><input type="hidden" name="action" value="create">
          <div class="mb-3">
            <label class="form-label">Group name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Placement Prep 2026" required maxlength="120">
          </div>
          <button class="btn btn-info w-100">Create group</button>
        </form>
      </div></div>
    </div>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100"><div class="card-body">
        <h5><i class="bi bi-box-arrow-in-right"></i> Join a group</h5>
        <p class="text-muted small">Enter the code shared by your group creator.</p>
        <form method="post">
          <?= csrf_field() ?><input type="hidden" name="action" value="join">
          <div class="mb-3">
            <label class="form-label">Join code</label>
            <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g. K7P2QX" required
                   style="letter-spacing:3px" maxlength="12" autocomplete="off">
          </div>
          <button class="btn btn-outline-info w-100">Join group</button>
        </form>
      </div></div>
    </div>
  </div>
<?php endif; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
