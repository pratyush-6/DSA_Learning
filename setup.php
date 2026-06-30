<?php
/**
 * One-time setup: create the database, run the schema, and seed content.
 * Visit http://localhost/learn_dsa/setup.php in a browser, or run via CLI.
 * Safe to re-run (schema uses IF NOT EXISTS; seed is idempotent).
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$cli = PHP_SAPI === 'cli';
$log = [];
function step(string $msg, bool $ok = true): void
{
    global $log, $cli;
    $line = ($ok ? '[OK] ' : '[!!] ') . $msg;
    $log[] = [$ok, $msg];
    if ($cli) {
        echo $line . PHP_EOL;
    }
}

try {
    // 1. Create database if needed.
    db(false)->exec(
        'CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
    );
    step('Database "' . DB_NAME . '" ready.');

    // 2. Run schema.
    $sql = file_get_contents(__DIR__ . '/database/schema.sql');
    if ($sql === false) {
        throw new RuntimeException('Could not read schema.sql');
    }
    db()->exec($sql);
    step('Schema applied (tables created).');

    // 2b. Idempotent migrations (add indexes to pre-existing tables).
    $ensureIndex = function (string $table, string $index, string $cols): void {
        $stmt = db()->prepare(
            'SELECT COUNT(*) FROM information_schema.statistics
             WHERE table_schema = ? AND table_name = ? AND index_name = ?'
        );
        $stmt->execute([DB_NAME, $table, $index]);
        if ((int) $stmt->fetchColumn() === 0) {
            db()->exec("ALTER TABLE `{$table}` ADD INDEX `{$index}` ({$cols})");
        }
    };
    $ensureIndex('user_progress', 'idx_progress_completed', 'completed_at');
    $ensureIndex('user_problem_solved', 'idx_ups_solved', 'solved_at');
    step('Migrations applied (indexes ensured).');

    // 3. Seed reference + curriculum content.
    require __DIR__ . '/database/seed/seed.php';
    $seedResult = run_seed();
    foreach ($seedResult as $line) {
        step($line);
    }

    // 4. Import the Coder Army practice sheet (runs after chapters exist).
    require __DIR__ . '/database/seed/import_sheet.php';
    foreach (import_sheet() as $line) {
        step($line);
    }

    // 5. Seed built-in compiler coding exercises (test cases + starters).
    require __DIR__ . '/database/seed/import_exercises.php';
    foreach (import_exercises() as $line) {
        step($line);
    }

    // 6. Enable the compiler on selected sheet problems (statement + tests).
    require __DIR__ . '/database/seed/import_compiler_problems.php';
    foreach (import_compiler_problems() as $line) {
        step($line);
    }

    step('Setup complete.');
} catch (Throwable $ex) {
    step('Error: ' . $ex->getMessage(), false);
}

if ($cli) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Setup &middot; <?= htmlspecialchars(APP_NAME) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:760px">
  <h1 class="mb-4">⚙️ <?= htmlspecialchars(APP_NAME) ?> — Setup</h1>
  <ul class="list-group mb-4">
    <?php foreach ($log as [$ok, $msg]): ?>
      <li class="list-group-item d-flex align-items-center">
        <span class="badge bg-<?= $ok ? 'success' : 'danger' ?> me-2"><?= $ok ? '✓' : '✗' ?></span>
        <?= htmlspecialchars($msg) ?>
      </li>
    <?php endforeach; ?>
  </ul>
  <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary">Go to the platform →</a>
  <a href="<?= BASE_URL ?>/login.php" class="btn btn-outline-secondary">Login</a>
  <p class="text-muted small mt-3">
    Default admin login &mdash; email: <code>admin@dsa.test</code>, password: <code>admin123</code><br>
    (Change the password after first login.)
  </p>
</div>
</body>
</html>
