<?php
/**
 * CSRF token helpers. Requires an active session.
 */

declare(strict_types=1);

/** Return the current CSRF token, generating one if needed. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Hidden input field carrying the CSRF token. */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}

/** Validate a submitted token against the session token. */
function csrf_check(?string $token): bool
{
    return is_string($token)
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/** Abort the request if the POST CSRF token is invalid. */
function csrf_verify_or_die(): void
{
    $token = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
    if (!csrf_check($token)) {
        http_response_code(419);
        echo 'Invalid or expired CSRF token. Please refresh the page.';
        exit;
    }
}
