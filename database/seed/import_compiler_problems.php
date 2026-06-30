<?php
/**
 * Attach compiler data (statement, starters, test cases) to existing sheet
 * problems matched by topic+title. Idempotent.
 *
 * @return string[] log lines
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

function import_compiler_problems(): array
{
    $pdo = db();
    $items = require __DIR__ . '/compiler_problems.php';

    $updated = 0;
    $missing = [];
    foreach ($items as $it) {
        $slug = substr(slugify($it['topic'] . ' ' . $it['title']), 0, 200);
        $stmt = $pdo->prepare('SELECT id FROM practice_problems WHERE slug = ?');
        $stmt->execute([$slug]);
        $pid = $stmt->fetchColumn();
        if (!$pid) {
            $missing[] = $slug;
            continue;
        }
        $pid = (int) $pid;

        $pdo->prepare('UPDATE practice_problems SET statement_md=?, examples_md=?, constraints_md=? WHERE id=?')
            ->execute([$it['statement_md'], $it['examples_md'] ?? null, $it['constraints_md'] ?? null, $pid]);

        $pdo->prepare('DELETE FROM problem_testcases WHERE problem_id = ?')->execute([$pid]);
        $o = 0;
        foreach ($it['tests'] as $t) {
            $pdo->prepare('INSERT INTO problem_testcases (problem_id, stdin, expected_output, is_sample, sort_order) VALUES (?,?,?,?,?)')
                ->execute([$pid, $t['stdin'] ?? '', $t['expected'] ?? '', !empty($t['sample']) ? 1 : 0, $o++]);
        }

        $pdo->prepare('DELETE FROM problem_starters WHERE problem_id = ?')->execute([$pid]);
        foreach ($it['starters'] ?? [] as $lang => $code) {
            $pdo->prepare('INSERT INTO problem_starters (problem_id, language, code) VALUES (?,?,?)')
                ->execute([$pid, $lang, $code]);
        }
        $updated++;
    }

    $log = ["Enabled the compiler on {$updated} sheet problems."];
    if ($missing) {
        $log[] = 'Compiler targets not found (skipped): ' . implode(', ', $missing);
    }
    return $log;
}
