<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/groups.php';
require_login();

$uid  = current_user_id();
$grp  = user_group($uid);
$scope = (isset($_GET['scope']) && $_GET['scope'] === 'group' && $grp) ? 'group' : 'global';

/** Build a ranked leaderboard. Returns rows with rank, name, modules, questions, score. */
function leaderboard_rows(?int $groupId = null, int $limit = 25): array
{
    $where = '';
    $params = [];
    if ($groupId) {
        $where = 'JOIN group_members gm ON gm.user_id = u.id AND gm.group_id = ?';
        $params[] = $groupId;
    }
    $sql = "SELECT u.id, u.name,
                   (SELECT COUNT(*) FROM user_progress up WHERE up.user_id=u.id AND up.status='completed') AS modules,
                   (SELECT COUNT(*) FROM user_problem_solved ps WHERE ps.user_id=u.id) AS questions
            FROM users u {$where}
            ORDER BY (modules + questions) DESC, modules DESC, u.name
            LIMIT {$limit}";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();
    $rank = 0;
    foreach ($rows as &$r) {
        $r['rank']  = ++$rank;
        $r['score'] = (int) $r['modules'] + (int) $r['questions'];
        $r['streak'] = current_streak((int) $r['id']);
    }
    return $rows;
}

$rows = $scope === 'group' ? leaderboard_rows((int) $grp['id']) : leaderboard_rows(null);

$pageTitle = 'Leaderboard';
require __DIR__ . '/partials/header.php';
?>
<div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
  <div>
    <h2 class="mb-1"><i class="bi bi-trophy text-warning"></i> Leaderboard</h2>
    <p class="text-muted mb-0">Ranked by modules completed + questions solved.</p>
  </div>
  <div class="segmented">
    <a class="<?= $scope === 'global' ? 'active' : '' ?>" href="?scope=global">Global</a>
    <a class="<?= $scope === 'group' ? 'active' : '' ?>" href="<?= $grp ? '?scope=group' : 'groups.php' ?>">My group</a>
  </div>
</div>

<?php if ($scope === 'group' && !$grp): ?>
  <div class="empty-state"><i class="bi bi-people"></i><p class="mt-2">Join a group to see its leaderboard.</p>
    <a class="btn btn-primary" href="<?= url('groups.php') ?>">Go to Group Study</a></div>
<?php elseif (!$rows): ?>
  <div class="empty-state"><i class="bi bi-bar-chart"></i><p class="mt-2">No activity yet. Be the first to climb the board!</p></div>
<?php else: ?>

  <!-- Podium (top 3) -->
  <?php $top = array_slice($rows, 0, 3); if (count($top) >= 1): ?>
  <div class="row g-3 mb-4 justify-content-center">
    <?php
      $order = count($top) >= 3 ? [1, 0, 2] : array_keys($top); // center the #1
      foreach ($order as $idx): if (!isset($top[$idx])) continue; $t = $top[$idx]; $isMe = (int)$t['id']===$uid;
    ?>
      <div class="col-6 col-md-4">
        <div class="card lift text-center <?= $idx===0 ? 'mt-0' : 'mt-md-3' ?> <?= $isMe ? 'border-primary' : '' ?>">
          <div class="card-body">
            <div class="medal medal-<?= $t['rank'] ?> mx-auto mb-2" style="width:46px;height:46px;font-size:1.1rem"><?= $t['rank'] ?></div>
            <div class="fw-bold text-truncate"><?= e($t['name']) ?><?= $isMe ? ' <span class="badge bg-primary">You</span>' : '' ?></div>
            <div class="display-6 fw-bold" style="color:var(--brand)"><?= (int) $t['score'] ?></div>
            <div class="small text-muted"><?= (int) $t['modules'] ?> modules · <?= (int) $t['questions'] ?> questions</div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="card"><div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead><tr><th class="ps-3">Rank</th><th>Learner</th><th class="text-center">Modules</th><th class="text-center">Questions</th><th class="text-center">Streak</th><th class="text-center pe-3">Score</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): $isMe = (int) $r['id'] === $uid; ?>
            <tr<?= $isMe ? ' style="background:rgba(99,102,241,.08)"' : '' ?>>
              <td class="ps-3">
                <?php if ($r['rank'] <= 3): ?><span class="medal medal-<?= $r['rank'] ?>"><?= $r['rank'] ?></span>
                <?php else: ?><span class="fw-bold text-muted">#<?= $r['rank'] ?></span><?php endif; ?>
              </td>
              <td>
                <span class="d-inline-grid bg-grad me-2" style="width:30px;height:30px;border-radius:50%;place-items:center;color:#fff;font-weight:700;vertical-align:middle"><?= e(strtoupper(substr($r['name'],0,1))) ?></span>
                <?= e($r['name']) ?><?= $isMe ? ' <span class="badge bg-primary">You</span>' : '' ?>
              </td>
              <td class="text-center fw-semibold"><?= (int) $r['modules'] ?></td>
              <td class="text-center fw-semibold"><?= (int) $r['questions'] ?></td>
              <td class="text-center"><?= $r['streak'] > 0 ? '🔥 ' . (int) $r['streak'] : '—' ?></td>
              <td class="text-center pe-3"><span class="badge bg-grad fs-6"><?= (int) $r['score'] ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div></div>
<?php endif; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
