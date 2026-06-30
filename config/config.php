<?php
/**
 * Global configuration for the DSA Learning Platform.
 *
 * Local overrides: create config/config.local.php (git-ignored) to override any
 * of the constants below — e.g. real DB credentials in production. See
 * config/config.local.example.php. Because every constant uses a
 * "defined() || define()" guard, anything defined in the local file wins.
 */

declare(strict_types=1);

// Load machine-specific overrides first (kept out of version control).
$__local = __DIR__ . '/config.local.php';
if (is_file($__local)) {
    require $__local;
}

// ---- Database ---------------------------------------------------------------
defined('DB_HOST')    || define('DB_HOST', '127.0.0.1');
defined('DB_PORT')    || define('DB_PORT', '3306');
defined('DB_NAME')    || define('DB_NAME', 'learn_dsa');
defined('DB_USER')    || define('DB_USER', 'root');
defined('DB_PASS')    || define('DB_PASS', '');
defined('DB_CHARSET') || define('DB_CHARSET', 'utf8mb4');

// ---- Application ------------------------------------------------------------
defined('APP_NAME') || define('APP_NAME', 'DSA Learning Platform');

// Base URL path under which the app is served (no trailing slash).
defined('BASE_URL') || define('BASE_URL', '/learn_dsa');

// Absolute filesystem root of the application.
define('APP_ROOT', dirname(__DIR__));

// Supported programming languages: key => display label.
defined('LANGUAGES') || define('LANGUAGES', [
    'php'    => 'PHP',
    'cpp'    => 'C++',
    'java'   => 'Java',
    'python' => 'Python',
]);

// Difficulty levels for chapters.
defined('LEVELS') || define('LEVELS', [
    'beginner'     => 'Beginner',
    'intermediate' => 'Intermediate',
    'advanced'     => 'Advanced',
]);

// ---- Built-in code compiler / runner ---------------------------------------
// Local interpreters (used when available; offline & private). On non-Windows
// or when these paths are missing, the runner falls back to the PHP CLI on
// PATH / PHP_BINARY and to python3 automatically.
defined('PHP_CLI')    || define('PHP_CLI', 'C:\\xampp\\php\\php.exe');
defined('PYTHON_BIN') || define('PYTHON_BIN', 'python');
defined('GPP_BIN')    || define('GPP_BIN', 'g++');
defined('JAVAC_BIN')  || define('JAVAC_BIN', 'javac');
defined('JAVA_BIN')   || define('JAVA_BIN', 'java');

// Per-run limits.
defined('EXEC_TIME_LIMIT_MS') || define('EXEC_TIME_LIMIT_MS', 6000);
defined('EXEC_OUTPUT_LIMIT')  || define('EXEC_OUTPUT_LIMIT', 200000);

// Remote execution (Piston) for languages with no local toolchain (e.g. C++/Java).
defined('EXEC_ENABLE_REMOTE') || define('EXEC_ENABLE_REMOTE', true);
defined('PISTON_URL')         || define('PISTON_URL', 'https://emkc.org/api/v2/piston');

// Error reporting (development). Set APP_DEBUG=false in config.local.php for prod.
defined('APP_DEBUG') || define('APP_DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? '1' : '0');

date_default_timezone_set('Asia/Kolkata');
