<?php
/**
 * Session bootstrap and authentication guards.
 * Include this at the top of every page.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/** Is a user currently logged in? */
function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

/** The current user's id, or null. */
function current_user_id(): ?int
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

/** The current user record (cached per request), or null. */
function current_user(): ?array
{
    static $user = null;
    static $loaded = false;
    if ($loaded) {
        return $user;
    }
    $loaded = true;
    if (!is_logged_in()) {
        return null;
    }
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([current_user_id()]);
    $user = $stmt->fetch() ?: null;
    return $user;
}

/** The current user's preferred language key, defaulting to PHP. */
function current_language(): string
{
    $user = current_user();
    return $user['preferred_language'] ?? 'php';
}

function is_admin(): bool
{
    $user = current_user();
    return $user !== null && $user['role'] === 'admin';
}

/** Require an authenticated user; redirect to login otherwise. */
function require_login(): void
{
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? null;
        redirect('login.php');
    }
}

/** Require an admin user. */
function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        echo 'Access denied: administrators only.';
        exit;
    }
}

/** Log a user in by id. */
function login_user(int $userId): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    record_activity($userId);
}

/** Log out the current user. */
function logout_user(): void
{
    $_SESSION = [];
    session_destroy();
}
