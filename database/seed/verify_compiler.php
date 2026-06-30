<?php
/**
 * Self-check: runs each compiler problem's reference solution against its test
 * cases and confirms the seeded expected outputs are correct.
 *
 * Usage: php database/seed/verify_compiler.php   (exit 0 = all good)
 */

declare(strict_types=1);
error_reporting(E_ALL & ~E_DEPRECATED);

require_once __DIR__ . '/../../includes/runner.php';

$items = require __DIR__ . '/compiler_problems.php';
$fail = 0;
$checked = 0;

foreach ($items as $it) {
    $ref = $it['reference'] ?? null;
    if (!$ref) {
        echo "[skip] {$it['title']} (no reference)\n";
        continue;
    }
    foreach ($it['tests'] as $i => $t) {
        $checked++;
        $r = run_code('python', $ref, (string) ($t['stdin'] ?? ''));
        $got = normalize_output($r['stdout']);
        $exp = normalize_output((string) ($t['expected'] ?? ''));
        if (!$r['ok'] || $got !== $exp) {
            $fail++;
            echo "[FAIL] {$it['topic']}::{$it['title']} case #" . ($i + 1) . "\n";
            echo "       stdin=" . json_encode($t['stdin']) . "\n";
            echo "       expected=" . json_encode($exp) . " got=" . json_encode($got) . "\n";
            if ($r['stderr']) echo "       stderr=" . trim($r['stderr']) . "\n";
        }
    }
}

echo "\nVerified {$checked} cases across " . count($items) . " problems: " . ($fail ? "{$fail} FAILED" : 'ALL OK') . "\n";
exit($fail === 0 ? 0 : 1);
