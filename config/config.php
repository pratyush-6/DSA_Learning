<?php
/**
 * Global configuration for the DSA Learning Platform.
 */

declare(strict_types=1);

// ---- Database ---------------------------------------------------------------
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'learn_dsa');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ---- Application ------------------------------------------------------------
define('APP_NAME', 'DSA Learning Platform');

// Base URL path under which the app is served (no trailing slash).
// On default XAMPP this is "/learn_dsa".
define('BASE_URL', '/learn_dsa');

// Absolute filesystem root of the application.
define('APP_ROOT', dirname(__DIR__));

// Supported programming languages: key => display label.
define('LANGUAGES', [
    'php'    => 'PHP',
    'cpp'    => 'C++',
    'java'   => 'Java',
    'python' => 'Python',
]);

// Difficulty levels for chapters.
define('LEVELS', [
    'beginner'     => 'Beginner',
    'intermediate' => 'Intermediate',
    'advanced'     => 'Advanced',
]);

// ---- Built-in code compiler / runner ---------------------------------------
// Local interpreters (used when available; offline & private).
define('PHP_CLI', 'C:\\xampp\\php\\php.exe');   // PHP CLI binary
define('PYTHON_BIN', 'python');                  // Python on PATH
define('GPP_BIN', 'g++');                         // C++ compiler (optional)
define('JAVAC_BIN', 'javac');                     // Java compiler (optional)
define('JAVA_BIN', 'java');

// Per-run limits.
define('EXEC_TIME_LIMIT_MS', 6000);
define('EXEC_OUTPUT_LIMIT', 200000);             // bytes of captured output

// Remote execution (Piston) for languages with no local toolchain (e.g. C++/Java).
// Code for those languages is sent to this public service ONLY when run.
define('EXEC_ENABLE_REMOTE', true);
define('PISTON_URL', 'https://emkc.org/api/v2/piston');

// Error reporting (development).
error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('Asia/Kolkata');
