<?php
/**
 * Imports every question from the Coder Army practice sheet
 * (database/seed/coder-army-sheet.csv) into practice_problems, mapped to the
 * matching chapter. Curated 4-language solutions from sheet_solutions.php are
 * attached where available.
 *
 * Idempotent: problems are upserted by slug; their solutions are replaced.
 *
 * @return string[] log lines
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

function import_sheet(): array
{
    $pdo = db();
    $log = [];

    $csvPath = __DIR__ . '/coder-army-sheet.csv';
    if (!is_file($csvPath)) {
        return ['Sheet CSV not found: ' . $csvPath];
    }

    // Sheet "Topic" -> chapter slug (chapters created by content seeders).
    $topicToChapter = [
        'Array'                 => 'arrays',
        'String'                => 'strings',
        'Searching and Sorting' => 'searching-algorithms',
        'LinkedList'            => 'linked-lists',
        'Stack'                 => 'stacks',
        'Queue'                 => 'queues',
        'Tree'                  => 'trees',
        'Binary Search Tree'    => 'binary-search-trees',
        'Heaps'                 => 'heaps',
        'Greedy'                => 'greedy-algorithms',
        'BackTracking'          => 'backtracking',
        'Hashing'               => 'hashing',
        'Graphs'                => 'graphs',
        'Dynamic Programming'   => 'dynamic-programming',
        'Segment Tree'          => 'segment-trees',
        'Trie'                  => 'tries',
        'Fenwick Tree'          => 'fenwick-tree',
    ];

    // Resolve chapter ids once.
    $chapterId = [];
    foreach (array_unique(array_values($topicToChapter)) as $slug) {
        $stmt = $pdo->prepare('SELECT id FROM chapters WHERE slug = ?');
        $stmt->execute([$slug]);
        $id = $stmt->fetchColumn();
        if ($id) {
            $chapterId[$slug] = (int) $id;
        }
    }

    $solutions = require __DIR__ . '/sheet_solutions.php';

    $lines = file($csvPath, FILE_IGNORE_NEW_LINES) ?: [];
    $seenSlugs = [];
    $imported = $withSolutions = $skipped = 0;

    foreach ($lines as $line) {
        $cols  = str_getcsv($line, ',', '"', '\\');
        $topic = trim($cols[0] ?? '');
        $title = trim($cols[1] ?? '');
        $level = strtolower(trim($cols[2] ?? ''));
        $day   = (int) trim($cols[3] ?? '0');

        if ($topic === '' || strtolower($topic) === 'topic' || $title === '') {
            continue; // promo/header/blank row
        }
        if (!isset($topicToChapter[$topic]) || !isset($chapterId[$topicToChapter[$topic]])) {
            $skipped++;
            continue;
        }

        $cid        = $chapterId[$topicToChapter[$topic]];
        $difficulty = in_array($level, ['easy', 'medium', 'hard'], true) ? $level : 'medium';

        // Unique slug.
        $base = substr(slugify($topic . ' ' . $title), 0, 200);
        if ($base === '') {
            continue;
        }
        $slug = $base;
        $n = 2;
        while (isset($seenSlugs[$slug])) {
            $slug = substr($base, 0, 196) . '-' . $n++;
        }
        $seenSlugs[$slug] = true;

        $solKey  = $topic . '::' . $title;
        $hasSol  = isset($solutions[$solKey]);
        $note    = $hasSol
            ? 'A reference solution is provided below in all supported languages.'
            : '_Reference solution not yet added — try solving it yourself, or add one via the admin tool._';

        $statement = "**" . $title . "**\n\n"
            . "Practice problem from the **Coder Army DSA sheet** — Topic: *" . $topic . "*"
            . ($day > 0 ? ", Day " . $day : "") . ".\n\n"
            . "Read the full statement on your preferred judge (GeeksforGeeks / LeetCode), "
            . "then implement and verify your approach.\n\n" . $note;

        // Upsert problem by slug.
        $stmt = $pdo->prepare('SELECT id FROM practice_problems WHERE slug = ?');
        $stmt->execute([$slug]);
        $pid = $stmt->fetchColumn();

        if ($pid) {
            $pid = (int) $pid;
            $pdo->prepare(
                'UPDATE practice_problems SET chapter_id=?, title=?, difficulty=?, statement_md=?, sort_order=? WHERE id=?'
            )->execute([$cid, $title, $difficulty, $statement, $day, $pid]);
        } else {
            $pdo->prepare(
                'INSERT INTO practice_problems (chapter_id, title, slug, difficulty, statement_md, sort_order)
                 VALUES (?,?,?,?,?,?)'
            )->execute([$cid, $title, $slug, $difficulty, $statement, $day]);
            $pid = (int) $pdo->lastInsertId();
        }
        $imported++;

        // Attach curated solutions (replace existing for this problem).
        if ($hasSol) {
            $pdo->prepare('DELETE FROM practice_solutions WHERE problem_id = ?')->execute([$pid]);
            foreach ($solutions[$solKey] as $lang => $sol) {
                if (!isset(LANGUAGES[$lang]) || empty($sol['code'])) {
                    continue;
                }
                $pdo->prepare(
                    'INSERT INTO practice_solutions (problem_id, language, code, explanation_md) VALUES (?,?,?,?)'
                )->execute([$pid, $lang, $sol['code'], $sol['explanation_md'] ?? null]);
            }
            $withSolutions++;
        }
    }

    $log[] = "Imported {$imported} problems from the sheet ({$withSolutions} with full 4-language solutions).";
    if ($skipped > 0) {
        $log[] = "Skipped {$skipped} rows (unmapped topic / missing chapter).";
    }
    return $log;
}
