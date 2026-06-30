<?php
/**
 * Example local config. Copy to config/config.local.php (git-ignored) and edit.
 * Any constant defined here overrides the default in config/config.php.
 */

declare(strict_types=1);

// Database credentials for this machine / environment.
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'learn_dsa');
define('DB_USER', 'root');
define('DB_PASS', '');            // set a real password in production

// URL path the app is served under (no trailing slash).
define('BASE_URL', '/learn_dsa');

// Turn off verbose errors in production.
define('APP_DEBUG', false);

// Optional: disable sending C++/Java code to the public Piston API.
// define('EXEC_ENABLE_REMOTE', false);

// Optional: point at local interpreters explicitly.
// define('PHP_CLI', '/usr/bin/php');
// define('PYTHON_BIN', 'python3');
