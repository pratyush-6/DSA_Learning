<?php
/**
 * Lightweight file-based sliding-window rate limiter.
 * Suitable for a single-server install (no external store required).
 */

declare(strict_types=1);

/**
 * Record a hit for $key and report whether it is allowed.
 *
 * @param string $key        unique bucket, e.g. "login:1.2.3.4"
 * @param int    $max        max allowed hits within the window
 * @param int    $windowSec  window length in seconds
 * @return array{allowed:bool, retry_after:int}
 */
function rate_limit(string $key, int $max, int $windowSec): array
{
    $dir = sys_get_temp_dir() . '/dsa_rl';
    if (!is_dir($dir)) {
        @mkdir($dir, 0700, true);
    }
    $file = $dir . '/' . hash('sha256', $key) . '.json';
    $now = time();

    $fp = @fopen($file, 'c+');
    if (!$fp) {
        // Fail open if the temp store is unavailable.
        return ['allowed' => true, 'retry_after' => 0];
    }
    flock($fp, LOCK_EX);
    $raw = stream_get_contents($fp);
    $hits = $raw ? (json_decode($raw, true) ?: []) : [];

    // Drop timestamps outside the window.
    $hits = array_values(array_filter($hits, fn($t) => $t > $now - $windowSec));

    $allowed = count($hits) < $max;
    if ($allowed) {
        $hits[] = $now;
    }

    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($hits));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);

    $retry = $allowed ? 0 : max(1, ($hits[0] ?? $now) + $windowSec - $now);
    return ['allowed' => $allowed, 'retry_after' => $retry];
}

/** Best-effort client IP. */
function client_ip(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}
