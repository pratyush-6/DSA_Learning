<?php
/**
 * Comprehensive test suite for Group Study, Module Completion, and Analytics.
 *
 * Run from the CLI:  php tests/run_tests.php
 * Creates isolated test users/groups (prefixed) and removes them at the end.
 * Exits non-zero if any test fails.
 */

declare(strict_types=1);
error_reporting(E_ALL & ~E_DEPRECATED);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/groups.php';

$pdo = db();

$passed = 0;
$failed = 0;
function check(string $name, bool $cond): void
{
    global $passed, $failed;
    if ($cond) { $passed++; echo "  [PASS] $name\n"; }
    else       { $failed++; echo "  [FAIL] $name\n"; }
}
function section(string $s): void { echo "\n== $s ==\n"; }

// ---------------------------------------------------------------------------
// Setup: clean any leftovers, create test users.
// ---------------------------------------------------------------------------
$TEST_EMAIL = 'grptest_%@dsa.test';
$pdo->prepare("DELETE FROM study_groups WHERE name LIKE 'GRPTEST_%'")->execute();
$pdo->prepare("DELETE FROM users WHERE email LIKE ?")->execute([$TEST_EMAIL]);

function make_user(string $tag): int
{
    $pdo = db();
    $pdo->prepare('INSERT INTO users (name, email, password_hash, preferred_language) VALUES (?,?,?,?)')
        ->execute(["Test $tag", "grptest_$tag@dsa.test", password_hash('x', PASSWORD_DEFAULT), 'php']);
    return (int) $pdo->lastInsertId();
}

$alice = make_user('alice');
$bob   = make_user('bob');
$carol = make_user('carol');

// A real topic & problem id to test completion/solving against.
$topicId   = (int) $pdo->query('SELECT id FROM topics ORDER BY id LIMIT 1')->fetchColumn();
$topicId2  = (int) $pdo->query('SELECT id FROM topics ORDER BY id LIMIT 1 OFFSET 1')->fetchColumn();
$problemId = (int) $pdo->query('SELECT id FROM practice_problems ORDER BY id LIMIT 1')->fetchColumn();

// ===========================================================================
section('GROUP STUDY');
// ===========================================================================

// Create a new group.
$r = create_group($alice, 'GRPTEST_Alpha');
check('Create a new group succeeds', $r['ok'] === true);
$g = user_group($alice);
check('Creator is auto-added as a member', $g !== null && $g['name'] === 'GRPTEST_Alpha');
check('Group has a non-empty join code', $g !== null && strlen($g['join_code']) >= 6);
$codeAlpha = $g['join_code'] ?? '';

// Unique join code generation.
$r2 = create_group($bob, 'GRPTEST_Beta');
$gBeta = user_group($bob);
check('Second group gets a DIFFERENT join code', $gBeta && $gBeta['join_code'] !== $codeAlpha);
$codes = [];
for ($i = 0; $i < 200; $i++) { $codes[generate_join_code()] = true; }
check('200 generated codes are (practically) unique', count($codes) >= 199);
check('Join code uses safe charset (no 0/O/1/I)', !preg_match('/[01OI]/', $codeAlpha));

// Bob leaves Beta so he can test joining Alpha later.
leave_group($bob);
check('Beta auto-deleted after its only member left',
    (int) $pdo->query("SELECT COUNT(*) FROM study_groups WHERE name='GRPTEST_Beta'")->fetchColumn() === 0);

// Join a group using a valid code.
$r = join_group($bob, $codeAlpha);
check('Join with a valid code succeeds', $r['ok'] === true);
check('Bob is now in Alice\'s group', (function () use ($bob, $alice) {
    $gb = user_group($bob); $ga = user_group($alice);
    return $gb && $ga && (int) $gb['id'] === (int) $ga['id'];
})());

// Join with lowercase code should still work (normalized).
$r = join_group($carol, strtolower($codeAlpha));
check('Join code is case-insensitive', $r['ok'] === true);

// Attempt to join with an invalid code.
$dave = make_user('dave');
$r = join_group($dave, 'ZZZZZZ');
check('Join with an invalid code is rejected', $r['ok'] === false && str_contains(strtolower($r['error']), 'invalid'));
check('Rejected user is NOT in any group', user_group($dave) === null);

// Prevent joining when already a member of another group.
$r = join_group($bob, $codeAlpha);
check('Joining again while already a member is rejected', $r['ok'] === false);
$r = create_group($bob, 'GRPTEST_Gamma');
check('Creating a second group while in one is rejected', $r['ok'] === false);

// One-group-per-user is also enforced at the DB level (PK on user_id).
$dbEnforced = false;
try {
    $pdo->prepare('INSERT INTO group_members (user_id, group_id) VALUES (?, ?)')
        ->execute([$bob, (int) user_group($alice)['id']]);
} catch (Throwable $e) { $dbEnforced = true; }
check('DB primary key blocks a duplicate membership row', $dbEnforced);

// Leave a group and join another.
leave_group($bob);
check('Leave group works', user_group($bob) === null);
create_group($bob, 'GRPTEST_Delta');
$gDelta = user_group($bob);
check('After leaving, user can join/create another group', $gDelta && $gDelta['name'] === 'GRPTEST_Delta');

// ===========================================================================
section('MODULE COMPLETION');
// ===========================================================================

/** Mirrors api/progress.php completion logic. */
function mark_complete(int $userId, int $topicId): void
{
    db()->prepare(
        'INSERT INTO user_progress (user_id, topic_id, status, completed_at)
         VALUES (?,?,"completed",NOW())
         ON DUPLICATE KEY UPDATE status="completed", completed_at=NOW()'
    )->execute([$userId, $topicId]);
}

$pdo->prepare('DELETE FROM user_progress WHERE user_id = ?')->execute([$alice]);
mark_complete($alice, $topicId);
$row = (function () use ($pdo, $alice, $topicId) {
    $s = $pdo->prepare('SELECT * FROM user_progress WHERE user_id=? AND topic_id=?');
    $s->execute([$alice, $topicId]); return $s->fetch();
})();
check('Module marked completed (row exists)', $row !== false);
check('Status is "completed"', $row && $row['status'] === 'completed');
check('Completion timestamp recorded', $row && !empty($row['completed_at']));
check('Completion date is "now" (within 2 min)', $row && abs(time() - strtotime($row['completed_at'])) < 120);

// Prevent duplicate completion records.
mark_complete($alice, $topicId);
mark_complete($alice, $topicId);
$cnt = (int) (function () use ($pdo, $alice, $topicId) {
    $s = $pdo->prepare('SELECT COUNT(*) FROM user_progress WHERE user_id=? AND topic_id=?');
    $s->execute([$alice, $topicId]); return $s->fetchColumn();
})();
check('Marking the same module repeatedly creates only ONE record', $cnt === 1);

// Persistence: a fresh query returns the completed status (simulates page refresh).
$persist = (bool) (function () use ($pdo, $alice, $topicId) {
    $s = $pdo->prepare('SELECT 1 FROM user_progress WHERE user_id=? AND topic_id=? AND status="completed"');
    $s->execute([$alice, $topicId]); return $s->fetchColumn();
})();
check('Completion persists on re-query (refresh-safe)', $persist);

// ===========================================================================
section('QUESTIONS SOLVED');
// ===========================================================================

/** Mirrors api/solve.php logic. */
function mark_solved(int $userId, int $problemId): void
{
    db()->prepare('INSERT IGNORE INTO user_problem_solved (user_id, problem_id, solved_at) VALUES (?,?,NOW())')
        ->execute([$userId, $problemId]);
}

$pdo->prepare('DELETE FROM user_problem_solved WHERE user_id = ?')->execute([$alice]);
mark_solved($alice, $problemId);
mark_solved($alice, $problemId); // duplicate attempt
$sc = (int) (function () use ($pdo, $alice, $problemId) {
    $s = $pdo->prepare('SELECT COUNT(*) FROM user_problem_solved WHERE user_id=? AND problem_id=?');
    $s->execute([$alice, $problemId]); return $s->fetchColumn();
})();
check('Question marked solved, duplicate prevented (one record)', $sc === 1);

// ===========================================================================
section('DASHBOARD & ANALYTICS');
// ===========================================================================

// Put Alice, Carol into a fresh comparison group with known activity.
leave_group($alice); leave_group($carol); leave_group($bob);
create_group($alice, 'GRPTEST_Dash');
$dash = user_group($alice);
join_group($carol, $dash['join_code']);

// Activity: Alice completes 2 modules + solves 1 question; Carol completes 1 module.
$pdo->prepare('DELETE FROM user_progress WHERE user_id IN (?,?)')->execute([$alice, $carol]);
$pdo->prepare('DELETE FROM user_problem_solved WHERE user_id IN (?,?)')->execute([$alice, $carol]);
mark_complete($alice, $topicId);
mark_complete($alice, $topicId2);
mark_solved($alice, $problemId);
mark_complete($carol, $topicId);

$stats = group_member_stats((int) $dash['id']);
$byId = [];
foreach ($stats as $s) { $byId[(int) $s['id']] = $s; }
check('Dashboard lists all group members', count($stats) === 2);
check('Module count accurate for member A (2)', (int) ($byId[$alice]['modules_completed'] ?? -1) === 2);
check('Module count accurate for member B (1)', (int) ($byId[$carol]['modules_completed'] ?? -1) === 1);
check('Question count accurate for member A (1)', (int) ($byId[$alice]['questions_solved'] ?? -1) === 1);
check('Question count accurate for member B (0)', (int) ($byId[$carol]['questions_solved'] ?? -1) === 0);

// Completion history with dates.
$hist = group_completion_history((int) $dash['id']);
check('Completion history returns 3 rows (2 + 1)', count($hist) === 3);
check('Every history row has a completion date', (function () use ($hist) {
    foreach ($hist as $h) { if (empty($h['completed_at'])) return false; }
    return true;
})());

// Trend data (charts) updates with activity.
$trend = group_trend_data((int) $dash['id']);
check('Trend has at least one date bucket', count($trend['dates']) >= 1);
check('Trend has a cumulative series per active member', count($trend['series']) === 2);
check('Trend cumulative is monotonic non-decreasing', (function () use ($trend) {
    foreach ($trend['series'] as $s) {
        for ($i = 1; $i < count($s['cumulative']); $i++) {
            if ($s['cumulative'][$i] < $s['cumulative'][$i - 1]) return false;
        }
    }
    return true;
})());

// Empty/new group handled without errors.
$ok = true;
try {
    $emptyOwner = make_user('emptyowner');
    create_group($emptyOwner, 'GRPTEST_Empty');
    $eg = user_group($emptyOwner);
    $s1 = group_member_stats((int) $eg['id']);
    $s2 = group_completion_history((int) $eg['id']);
    $s3 = group_trend_data((int) $eg['id']);
    $ok = count($s1) === 1 && $s2 === [] && $s3['dates'] === [] && $s3['series'] === [];
} catch (Throwable $e) { $ok = false; }
check('Empty/new group produces no errors and empty analytics', $ok);

// ---------------------------------------------------------------------------
// Cleanup.
// ---------------------------------------------------------------------------
$pdo->prepare("DELETE FROM users WHERE email LIKE ?")->execute([$TEST_EMAIL]);
$pdo->prepare("DELETE FROM study_groups WHERE name LIKE 'GRPTEST_%'")->execute();

echo "\n----------------------------------------\n";
echo "RESULT: {$passed} passed, {$failed} failed\n";
exit($failed === 0 ? 0 : 1);
