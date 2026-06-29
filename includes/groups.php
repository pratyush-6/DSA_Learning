<?php
/**
 * Group Study helpers: join codes, create/join/leave, and member analytics.
 *
 * Rules enforced here AND by the schema (group_members PK = user_id):
 *  - a user can belong to only ONE group at a time.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

/** Generate a unique, human-friendly join code (no ambiguous chars). */
function generate_join_code(int $length = 6): string
{
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // no I,O,0,1
    do {
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        $stmt = db()->prepare('SELECT 1 FROM study_groups WHERE join_code = ?');
        $stmt->execute([$code]);
    } while ($stmt->fetchColumn());
    return $code;
}

/** The group a user currently belongs to, or null. */
function user_group(int $userId): ?array
{
    $stmt = db()->prepare(
        'SELECT g.*, gm.joined_at
         FROM group_members gm JOIN study_groups g ON g.id = gm.group_id
         WHERE gm.user_id = ?'
    );
    $stmt->execute([$userId]);
    return $stmt->fetch() ?: null;
}

/**
 * Create a new group and make the creator its first member.
 *
 * @return array{ok:bool, error?:string, group?:array}
 */
function create_group(int $userId, string $name): array
{
    $name = trim($name);
    if ($name === '') {
        return ['ok' => false, 'error' => 'Please enter a group name.'];
    }
    if (user_group($userId)) {
        return ['ok' => false, 'error' => 'You are already in a group. Leave it first.'];
    }

    $pdo = db();
    $pdo->beginTransaction();
    try {
        $code = generate_join_code();
        $pdo->prepare('INSERT INTO study_groups (name, join_code, created_by) VALUES (?,?,?)')
            ->execute([$name, $code, $userId]);
        $groupId = (int) $pdo->lastInsertId();
        $pdo->prepare('INSERT INTO group_members (user_id, group_id) VALUES (?, ?)')
            ->execute([$userId, $groupId]);
        $pdo->commit();
    } catch (Throwable $ex) {
        $pdo->rollBack();
        return ['ok' => false, 'error' => 'Could not create the group. Please try again.'];
    }
    return ['ok' => true, 'group' => user_group($userId)];
}

/**
 * Join a group by its code.
 *
 * @return array{ok:bool, error?:string, group?:array}
 */
function join_group(int $userId, string $code): array
{
    $code = strtoupper(trim($code));
    if ($code === '') {
        return ['ok' => false, 'error' => 'Please enter a join code.'];
    }
    if (user_group($userId)) {
        return ['ok' => false, 'error' => 'You are already in a group. Leave it before joining another.'];
    }

    $stmt = db()->prepare('SELECT id FROM study_groups WHERE join_code = ?');
    $stmt->execute([$code]);
    $groupId = $stmt->fetchColumn();
    if (!$groupId) {
        return ['ok' => false, 'error' => 'Invalid join code. Please check and try again.'];
    }

    try {
        db()->prepare('INSERT INTO group_members (user_id, group_id) VALUES (?, ?)')
            ->execute([$userId, (int) $groupId]);
    } catch (Throwable $ex) {
        // Race: PK on user_id already exists.
        return ['ok' => false, 'error' => 'You are already in a group.'];
    }
    return ['ok' => true, 'group' => user_group($userId)];
}

/** Remove the user from their current group. Empty groups are deleted. */
function leave_group(int $userId): array
{
    $group = user_group($userId);
    if (!$group) {
        return ['ok' => false, 'error' => 'You are not in a group.'];
    }
    db()->prepare('DELETE FROM group_members WHERE user_id = ?')->execute([$userId]);

    // Clean up empty groups so codes are not orphaned.
    $stmt = db()->prepare('SELECT COUNT(*) FROM group_members WHERE group_id = ?');
    $stmt->execute([(int) $group['id']]);
    if ((int) $stmt->fetchColumn() === 0) {
        db()->prepare('DELETE FROM study_groups WHERE id = ?')->execute([(int) $group['id']]);
    }
    return ['ok' => true];
}

/** Members of a group with their aggregate stats, ordered by modules completed. */
function group_member_stats(int $groupId): array
{
    $stmt = db()->prepare(
        'SELECT u.id, u.name, u.email, gm.joined_at,
                (SELECT COUNT(*) FROM user_progress up WHERE up.user_id = u.id AND up.status = "completed") AS modules_completed,
                (SELECT COUNT(*) FROM user_problem_solved ps WHERE ps.user_id = u.id) AS questions_solved,
                (SELECT COUNT(*) FROM user_quiz_attempts qa WHERE qa.user_id = u.id) AS quizzes_taken
         FROM group_members gm JOIN users u ON u.id = gm.user_id
         WHERE gm.group_id = ?
         ORDER BY modules_completed DESC, questions_solved DESC, u.name'
    );
    $stmt->execute([$groupId]);
    return $stmt->fetchAll();
}

/** Per-member module completion history (topic title, chapter, completion date). */
function group_completion_history(int $groupId): array
{
    $stmt = db()->prepare(
        'SELECT u.id AS user_id, u.name, t.title AS module, c.title AS chapter, up.completed_at
         FROM group_members gm
         JOIN users u ON u.id = gm.user_id
         JOIN user_progress up ON up.user_id = u.id AND up.status = "completed"
         JOIN topics t ON t.id = up.topic_id
         JOIN chapters c ON c.id = t.chapter_id
         WHERE gm.group_id = ?
         ORDER BY up.completed_at DESC'
    );
    $stmt->execute([$groupId]);
    return $stmt->fetchAll();
}

/**
 * Daily cumulative module-completion trend per member.
 * Returns ['dates' => [...], 'series' => [userId => ['name'=>, 'cumulative'=>[...]]]].
 */
function group_trend_data(int $groupId): array
{
    $stmt = db()->prepare(
        'SELECT up.user_id, u.name, DATE(up.completed_at) AS d, COUNT(*) AS cnt
         FROM group_members gm
         JOIN users u ON u.id = gm.user_id
         JOIN user_progress up ON up.user_id = u.id AND up.status = "completed" AND up.completed_at IS NOT NULL
         WHERE gm.group_id = ?
         GROUP BY up.user_id, u.name, DATE(up.completed_at)
         ORDER BY d'
    );
    $stmt->execute([$groupId]);
    $rows = $stmt->fetchAll();

    $dates = [];
    $perUserDay = [];
    $names = [];
    foreach ($rows as $r) {
        $dates[$r['d']] = true;
        $perUserDay[$r['user_id']][$r['d']] = (int) $r['cnt'];
        $names[$r['user_id']] = $r['name'];
    }
    $dates = array_keys($dates);
    sort($dates);

    $series = [];
    foreach ($perUserDay as $uid => $byDay) {
        $running = 0;
        $cumulative = [];
        foreach ($dates as $d) {
            $running += $byDay[$d] ?? 0;
            $cumulative[] = $running;
        }
        $series[$uid] = ['name' => $names[$uid], 'cumulative' => $cumulative];
    }

    return ['dates' => $dates, 'series' => $series];
}
