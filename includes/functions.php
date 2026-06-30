<?php
/**
 * Shared helper functions: rendering, URLs, progress, streaks, achievements.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/Parsedown.php';

/** Escape a string for safe HTML output. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Build an absolute URL within the application. */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** Build a URL to a static asset. */
function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

/** Render trusted Markdown content to HTML. */
function render_markdown(?string $markdown): string
{
    if ($markdown === null || trim($markdown) === '') {
        return '';
    }
    static $parser = null;
    if ($parser === null) {
        $parser = new Parsedown();
        $parser->setBreaksEnabled(true);
        // Content is authored by trusted admins/seeders, so inline HTML
        // (diagrams, tables) is allowed.
    }
    return $parser->text($markdown);
}

/** Convert a title into a URL-friendly slug. */
function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-');
}

/** Human-readable language label. */
function lang_label(string $key): string
{
    return LANGUAGES[$key] ?? strtoupper($key);
}

/** Redirect helper. */
function redirect(string $path): never
{
    header('Location: ' . (str_starts_with($path, 'http') ? $path : url($path)));
    exit;
}

/** Send a JSON response and stop. */
function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// ---------------------------------------------------------------------------
// Progress helpers
// ---------------------------------------------------------------------------

/** Count of completed topics for a user within a chapter. */
function chapter_progress(int $userId, int $chapterId): array
{
    $total = (int) db()->query(
        'SELECT COUNT(*) FROM topics WHERE chapter_id = ' . $chapterId
    )->fetchColumn();

    $stmt = db()->prepare(
        'SELECT COUNT(*) FROM user_progress up
         JOIN topics t ON t.id = up.topic_id
         WHERE up.user_id = ? AND t.chapter_id = ? AND up.status = "completed"'
    );
    $stmt->execute([$userId, $chapterId]);
    $done = (int) $stmt->fetchColumn();

    $percent = $total > 0 ? (int) round($done / $total * 100) : 0;
    return ['total' => $total, 'done' => $done, 'percent' => $percent];
}

/** Overall completion across all topics for a user. */
function overall_progress(int $userId): array
{
    $total = (int) db()->query('SELECT COUNT(*) FROM topics')->fetchColumn();
    $stmt  = db()->prepare(
        'SELECT COUNT(*) FROM user_progress WHERE user_id = ? AND status = "completed"'
    );
    $stmt->execute([$userId]);
    $done = (int) $stmt->fetchColumn();

    $percent = $total > 0 ? (int) round($done / $total * 100) : 0;
    return ['total' => $total, 'done' => $done, 'percent' => $percent];
}

/** Completion percentage for a difficulty level (beginner/intermediate/advanced). */
function level_progress(int $userId, string $level): array
{
    $stmt = db()->prepare(
        'SELECT COUNT(*) FROM topics t JOIN chapters c ON c.id = t.chapter_id WHERE c.level = ?'
    );
    $stmt->execute([$level]);
    $total = (int) $stmt->fetchColumn();

    $stmt = db()->prepare(
        'SELECT COUNT(*) FROM user_progress up
         JOIN topics t ON t.id = up.topic_id
         JOIN chapters c ON c.id = t.chapter_id
         WHERE up.user_id = ? AND c.level = ? AND up.status = "completed"'
    );
    $stmt->execute([$userId, $level]);
    $done = (int) $stmt->fetchColumn();

    $percent = $total > 0 ? (int) round($done / $total * 100) : 0;
    return ['total' => $total, 'done' => $done, 'percent' => $percent];
}

/** Set of completed topic IDs for a user (for ✓ rendering). */
function completed_topic_ids(int $userId): array
{
    $stmt = db()->prepare(
        'SELECT topic_id FROM user_progress WHERE user_id = ? AND status = "completed"'
    );
    $stmt->execute([$userId]);
    return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

// ---------------------------------------------------------------------------
// Streaks & activity
// ---------------------------------------------------------------------------

/** Record that the user was active today (idempotent). */
function record_activity(int $userId): void
{
    $stmt = db()->prepare(
        'INSERT IGNORE INTO user_activity (user_id, activity_date) VALUES (?, CURDATE())'
    );
    $stmt->execute([$userId]);
}

/** Compute a consecutive-day streak from activity dates (newest-first). */
function streak_from_dates(array $datesDesc): int
{
    if (!$datesDesc) {
        return 0;
    }
    $today     = new DateTimeImmutable('today');
    $yesterday = $today->modify('-1 day');
    $first     = new DateTimeImmutable($datesDesc[0]);

    if ($first->format('Y-m-d') !== $today->format('Y-m-d')
        && $first->format('Y-m-d') !== $yesterday->format('Y-m-d')) {
        return 0;
    }
    $streak   = 1;
    $previous = $first;
    for ($i = 1; $i < count($datesDesc); $i++) {
        $current  = new DateTimeImmutable($datesDesc[$i]);
        if ($current->format('Y-m-d') === $previous->modify('-1 day')->format('Y-m-d')) {
            $streak++;
            $previous = $current;
        } else {
            break;
        }
    }
    return $streak;
}

/** Current consecutive-day streak ending today or yesterday. */
function current_streak(int $userId): int
{
    $stmt = db()->prepare(
        'SELECT activity_date FROM user_activity WHERE user_id = ? ORDER BY activity_date DESC'
    );
    $stmt->execute([$userId]);
    return streak_from_dates($stmt->fetchAll(PDO::FETCH_COLUMN));
}

/**
 * Current streak for many users in a single query (avoids N+1 on leaderboards).
 *
 * @param int[] $userIds
 * @return array<int,int> userId => streak
 */
function streaks_for(array $userIds): array
{
    $userIds = array_values(array_unique(array_map('intval', $userIds)));
    if (!$userIds) {
        return [];
    }
    $in = implode(',', $userIds);
    $rows = db()->query(
        "SELECT user_id, activity_date FROM user_activity WHERE user_id IN ($in) ORDER BY user_id, activity_date DESC"
    )->fetchAll();

    $byUser = [];
    foreach ($rows as $r) {
        $byUser[(int) $r['user_id']][] = $r['activity_date'];
    }
    $out = [];
    foreach ($userIds as $uid) {
        $out[$uid] = streak_from_dates($byUser[$uid] ?? []);
    }
    return $out;
}

/** Longest streak ever achieved by the user. */
function longest_streak(int $userId): int
{
    $stmt = db()->prepare(
        'SELECT activity_date FROM user_activity WHERE user_id = ? ORDER BY activity_date ASC'
    );
    $stmt->execute([$userId]);
    $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (!$dates) {
        return 0;
    }

    $best = $run = 1;
    for ($i = 1; $i < count($dates); $i++) {
        $prev = new DateTimeImmutable($dates[$i - 1]);
        $curr = new DateTimeImmutable($dates[$i]);
        if ($curr->format('Y-m-d') === $prev->modify('+1 day')->format('Y-m-d')) {
            $run++;
            $best = max($best, $run);
        } else {
            $run = 1;
        }
    }
    return $best;
}

// ---------------------------------------------------------------------------
// Achievements
// ---------------------------------------------------------------------------

/** Award an achievement to a user if not already earned. Returns true if newly awarded. */
function award_achievement(int $userId, string $code): bool
{
    $stmt = db()->prepare('SELECT id FROM achievements WHERE code = ?');
    $stmt->execute([$code]);
    $achievementId = $stmt->fetchColumn();
    if (!$achievementId) {
        return false;
    }

    $stmt = db()->prepare(
        'INSERT IGNORE INTO user_achievements (user_id, achievement_id) VALUES (?, ?)'
    );
    $stmt->execute([$userId, (int) $achievementId]);
    return $stmt->rowCount() > 0;
}

/**
 * Re-evaluate milestone achievements for a user after a progress change.
 * Awards: first topic, 7-day streak, per-level completion, all complete.
 */
function evaluate_achievements(int $userId): void
{
    $overall = overall_progress($userId);
    if ($overall['done'] >= 1) {
        award_achievement($userId, 'first_topic');
    }
    if ($overall['total'] > 0 && $overall['done'] === $overall['total']) {
        award_achievement($userId, 'all_complete');
    }
    foreach (array_keys(LEVELS) as $level) {
        $p = level_progress($userId, $level);
        if ($p['total'] > 0 && $p['done'] === $p['total']) {
            award_achievement($userId, 'level_' . $level);
        }
    }
    if (current_streak($userId) >= 7) {
        award_achievement($userId, 'streak_7');
    }
}
