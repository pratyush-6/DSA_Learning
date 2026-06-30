<?php
/**
 * Seed the "Coding Exercises" chapter and its problems with test cases and
 * starter code for the built-in compiler. Idempotent (upsert by slug).
 *
 * @return string[] log lines
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

function import_exercises(): array
{
    $pdo = db();
    $exercises = require __DIR__ . '/exercises.php';

    // Upsert the chapter (sort_order 0 so it appears first in Beginner).
    $chapterId = ex_upsert($pdo, 'chapters', 'coding-exercises', [
        'title'       => 'Coding Exercises',
        'slug'        => 'coding-exercises',
        'level'       => 'beginner',
        'description' => 'Write, run, and test code in the built-in compiler. An exercise is completed only when all test cases pass.',
        'icon'        => 'bi-terminal',
        'sort_order'  => 0,
    ]);

    $count = $tcCount = 0;
    foreach ($exercises as $ex) {
        $pid = ex_upsert($pdo, 'practice_problems', $ex['slug'], [
            'chapter_id'     => $chapterId,
            'title'          => $ex['title'],
            'slug'           => $ex['slug'],
            'difficulty'     => $ex['difficulty'] ?? 'easy',
            'statement_md'   => $ex['statement_md'] ?? '',
            'constraints_md' => $ex['constraints_md'] ?? null,
            'examples_md'    => $ex['examples_md'] ?? null,
            'sort_order'     => $count,
        ]);

        // Replace test cases.
        $pdo->prepare('DELETE FROM problem_testcases WHERE problem_id = ?')->execute([$pid]);
        $o = 0;
        foreach ($ex['tests'] as $t) {
            $pdo->prepare(
                'INSERT INTO problem_testcases (problem_id, stdin, expected_output, is_sample, sort_order) VALUES (?,?,?,?,?)'
            )->execute([$pid, $t['stdin'] ?? '', $t['expected'] ?? '', !empty($t['sample']) ? 1 : 0, $o++]);
            $tcCount++;
        }

        // Replace starter code.
        $pdo->prepare('DELETE FROM problem_starters WHERE problem_id = ?')->execute([$pid]);
        foreach ($ex['starters'] ?? [] as $lang => $code) {
            $pdo->prepare('INSERT INTO problem_starters (problem_id, language, code) VALUES (?,?,?)')
                ->execute([$pid, $lang, $code]);
        }
        $count++;
    }

    return ["Seeded {$count} coding exercises with {$tcCount} test cases."];
}

/** Insert or update a row by its unique slug; returns its id. */
function ex_upsert(PDO $pdo, string $table, string $slug, array $fields): int
{
    $stmt = $pdo->prepare("SELECT id FROM {$table} WHERE slug = ?");
    $stmt->execute([$slug]);
    $id = $stmt->fetchColumn();
    if ($id) {
        $sets = [];
        $vals = [];
        foreach ($fields as $c => $v) { $sets[] = "{$c} = ?"; $vals[] = $v; }
        $vals[] = $id;
        $pdo->prepare("UPDATE {$table} SET " . implode(', ', $sets) . ' WHERE id = ?')->execute($vals);
        return (int) $id;
    }
    $cols = array_keys($fields);
    $ph = implode(', ', array_fill(0, count($cols), '?'));
    $pdo->prepare("INSERT INTO {$table} (" . implode(', ', $cols) . ") VALUES ({$ph})")->execute(array_values($fields));
    return (int) $pdo->lastInsertId();
}
