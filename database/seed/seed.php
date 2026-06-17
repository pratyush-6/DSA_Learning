<?php
/**
 * Seed runner. Loads reference data (achievements, companies, admin user) and
 * all curriculum content files in database/seed/content/*.php into the database.
 *
 * Idempotent: chapters/topics/problems are upserted by slug so user progress is
 * preserved across re-runs; per-chapter child rows (code, subtopics, interview
 * questions, quiz questions) are replaced.
 *
 * @return string[] log lines
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

function run_seed(): array
{
    $pdo = db();
    $log = [];

    // --- Achievements --------------------------------------------------------
    $achievements = [
        ['first_topic', 'First Steps', 'Completed your first topic.', 'bi-rocket-takeoff'],
        ['streak_7', 'On Fire', 'Maintained a 7-day learning streak.', 'bi-fire'],
        ['level_beginner', 'Beginner Conqueror', 'Completed every Beginner chapter.', 'bi-award'],
        ['level_intermediate', 'Intermediate Master', 'Completed every Intermediate chapter.', 'bi-award-fill'],
        ['level_advanced', 'Advanced Wizard', 'Completed every Advanced chapter.', 'bi-trophy'],
        ['all_complete', 'DSA Champion', 'Completed the entire curriculum.', 'bi-trophy-fill'],
        ['first_quiz', 'Quiz Taker', 'Completed your first quiz.', 'bi-patch-question'],
    ];
    $stmt = $pdo->prepare(
        'INSERT INTO achievements (code, title, description, icon) VALUES (?,?,?,?)
         ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), icon=VALUES(icon)'
    );
    foreach ($achievements as $a) {
        $stmt->execute($a);
    }
    $log[] = 'Seeded ' . count($achievements) . ' achievements.';

    // --- Companies -----------------------------------------------------------
    $companies = ['Google', 'Amazon', 'Microsoft', 'Meta', 'Apple', 'Netflix',
        'Adobe', 'Uber', 'Flipkart', 'Goldman Sachs', 'Atlassian', 'Bloomberg'];
    $stmt = $pdo->prepare('INSERT IGNORE INTO companies (name, slug) VALUES (?, ?)');
    foreach ($companies as $name) {
        $stmt->execute([$name, slugify($name)]);
    }
    $log[] = 'Seeded ' . count($companies) . ' companies.';

    // --- Default admin user --------------------------------------------------
    $exists = $pdo->query('SELECT COUNT(*) FROM users WHERE email = "admin@dsa.test"')->fetchColumn();
    if (!$exists) {
        $pdo->prepare(
            'INSERT INTO users (name, email, password_hash, role, preferred_language)
             VALUES (?,?,?,?,?)'
        )->execute([
            'Administrator', 'admin@dsa.test',
            password_hash('admin123', PASSWORD_DEFAULT), 'admin', 'php',
        ]);
        $log[] = 'Created default admin (admin@dsa.test / admin123).';
    } else {
        $log[] = 'Admin user already present.';
    }

    // --- Curriculum content --------------------------------------------------
    $files = glob(__DIR__ . '/content/*.php') ?: [];
    sort($files);
    $chapterCount = 0;
    foreach ($files as $file) {
        $data = require $file;
        if (!is_array($data) || empty($data['slug'])) {
            $log[] = 'Skipped invalid content file: ' . basename($file);
            continue;
        }
        seed_chapter($pdo, $data);
        $chapterCount++;
    }
    $log[] = "Seeded {$chapterCount} chapters from content files.";

    return $log;
}

/** Upsert a full chapter and its children. */
function seed_chapter(PDO $pdo, array $data): void
{
    // Chapter (upsert by slug).
    $chapterId = upsert_by_slug($pdo, 'chapters', $data['slug'], [
        'title'       => $data['title'],
        'slug'        => $data['slug'],
        'level'       => $data['level'] ?? 'beginner',
        'description' => $data['description'] ?? null,
        'icon'        => $data['icon'] ?? 'bi-journal-code',
        'sort_order'  => $data['sort_order'] ?? 0,
    ]);

    // Topics (upsert by slug to preserve progress).
    $order = 0;
    foreach ($data['topics'] ?? [] as $topic) {
        $topicId = upsert_by_slug($pdo, 'topics', $topic['slug'], [
            'chapter_id'    => $chapterId,
            'title'         => $topic['title'],
            'slug'          => $topic['slug'],
            'summary'       => $topic['summary'] ?? null,
            'theory_md'     => $topic['theory_md'] ?? null,
            'real_world_md' => $topic['real_world_md'] ?? null,
            'complexity_md' => $topic['complexity_md'] ?? null,
            'sort_order'    => $order++,
        ]);

        // Replace subtopics.
        $pdo->prepare('DELETE FROM subtopics WHERE topic_id = ?')->execute([$topicId]);
        $sOrder = 0;
        foreach ($topic['subtopics'] ?? [] as $sub) {
            $pdo->prepare(
                'INSERT INTO subtopics (topic_id, title, slug, body_md, sort_order) VALUES (?,?,?,?,?)'
            )->execute([
                $topicId, $sub['title'], slugify($topic['slug'] . '-' . $sub['title']),
                $sub['body_md'] ?? null, $sOrder++,
            ]);
        }

        // Replace code snippets.
        $pdo->prepare('DELETE FROM code_snippets WHERE topic_id = ?')->execute([$topicId]);
        $cOrder = 0;
        foreach ($topic['code'] ?? [] as $snip) {
            $pdo->prepare(
                'INSERT INTO code_snippets (topic_id, language, label, code, explanation_md, sort_order)
                 VALUES (?,?,?,?,?,?)'
            )->execute([
                $topicId, $snip['language'], $snip['label'] ?? 'Example',
                $snip['code'], $snip['explanation_md'] ?? null, $cOrder++,
            ]);
        }
    }

    // Interview questions (replace per chapter).
    $pdo->prepare('DELETE FROM interview_questions WHERE chapter_id = ?')->execute([$chapterId]);
    $iOrder = 0;
    foreach ($data['interview'] ?? [] as $q) {
        $pdo->prepare(
            'INSERT INTO interview_questions (chapter_id, type, difficulty, question, answer_md, sort_order)
             VALUES (?,?,?,?,?,?)'
        )->execute([
            $chapterId, $q['type'] ?? 'conceptual', $q['difficulty'] ?? 'easy',
            $q['question'], $q['answer_md'] ?? null, $iOrder++,
        ]);
        $qid = (int) $pdo->lastInsertId();
        foreach ($q['companies'] ?? [] as $companyName) {
            $cid = company_id($pdo, $companyName);
            if ($cid) {
                $pdo->prepare(
                    'INSERT IGNORE INTO interview_question_company (question_id, company_id) VALUES (?,?)'
                )->execute([$qid, $cid]);
            }
        }
    }

    // Practice problems (upsert by slug) + solutions (replace).
    $pOrder = 0;
    foreach ($data['problems'] ?? [] as $prob) {
        $problemId = upsert_by_slug($pdo, 'practice_problems', $prob['slug'], [
            'chapter_id'     => $chapterId,
            'title'          => $prob['title'],
            'slug'           => $prob['slug'],
            'difficulty'     => $prob['difficulty'] ?? 'easy',
            'statement_md'   => $prob['statement_md'] ?? '',
            'constraints_md' => $prob['constraints_md'] ?? null,
            'examples_md'    => $prob['examples_md'] ?? null,
            'sort_order'     => $pOrder++,
        ]);
        $pdo->prepare('DELETE FROM practice_solutions WHERE problem_id = ?')->execute([$problemId]);
        foreach ($prob['solutions'] ?? [] as $lang => $sol) {
            $pdo->prepare(
                'INSERT INTO practice_solutions (problem_id, language, code, explanation_md) VALUES (?,?,?,?)'
            )->execute([$problemId, $lang, $sol['code'], $sol['explanation_md'] ?? null]);
        }
    }

    // Quiz (one per chapter): upsert, then replace questions/options.
    if (!empty($data['quiz']['questions'])) {
        $stmt = $pdo->prepare('SELECT id FROM quizzes WHERE chapter_id = ? LIMIT 1');
        $stmt->execute([$chapterId]);
        $quizId = $stmt->fetchColumn();
        if ($quizId) {
            $pdo->prepare('UPDATE quizzes SET title = ? WHERE id = ?')
                ->execute([$data['quiz']['title'] ?? ($data['title'] . ' Quiz'), $quizId]);
            $pdo->prepare('DELETE FROM quiz_questions WHERE quiz_id = ?')->execute([$quizId]);
        } else {
            $pdo->prepare('INSERT INTO quizzes (chapter_id, title) VALUES (?, ?)')
                ->execute([$chapterId, $data['quiz']['title'] ?? ($data['title'] . ' Quiz')]);
            $quizId = (int) $pdo->lastInsertId();
        }
        $qOrder = 0;
        foreach ($data['quiz']['questions'] as $qq) {
            $pdo->prepare(
                'INSERT INTO quiz_questions (quiz_id, question, explanation_md, sort_order) VALUES (?,?,?,?)'
            )->execute([$quizId, $qq['question'], $qq['explanation_md'] ?? null, $qOrder++]);
            $questionId = (int) $pdo->lastInsertId();
            $oOrder = 0;
            foreach ($qq['options'] as $opt) {
                $pdo->prepare(
                    'INSERT INTO quiz_options (question_id, option_text, is_correct, sort_order) VALUES (?,?,?,?)'
                )->execute([$questionId, $opt['text'], !empty($opt['correct']) ? 1 : 0, $oOrder++]);
            }
        }
    }
}

/** Insert or update a row identified by its unique slug; returns the row id. */
function upsert_by_slug(PDO $pdo, string $table, string $slug, array $fields): int
{
    $stmt = $pdo->prepare("SELECT id FROM {$table} WHERE slug = ?");
    $stmt->execute([$slug]);
    $id = $stmt->fetchColumn();

    if ($id) {
        $sets = [];
        $vals = [];
        foreach ($fields as $col => $val) {
            $sets[] = "{$col} = ?";
            $vals[] = $val;
        }
        $vals[] = $id;
        $pdo->prepare("UPDATE {$table} SET " . implode(', ', $sets) . ' WHERE id = ?')->execute($vals);
        return (int) $id;
    }

    $cols = array_keys($fields);
    $ph   = implode(', ', array_fill(0, count($cols), '?'));
    $pdo->prepare(
        "INSERT INTO {$table} (" . implode(', ', $cols) . ") VALUES ({$ph})"
    )->execute(array_values($fields));
    return (int) $pdo->lastInsertId();
}

/** Resolve (creating if needed) a company id by name. */
function company_id(PDO $pdo, string $name): ?int
{
    $slug = slugify($name);
    $stmt = $pdo->prepare('SELECT id FROM companies WHERE slug = ?');
    $stmt->execute([$slug]);
    $id = $stmt->fetchColumn();
    if ($id) {
        return (int) $id;
    }
    $pdo->prepare('INSERT INTO companies (name, slug) VALUES (?, ?)')->execute([$name, $slug]);
    return (int) $pdo->lastInsertId();
}
