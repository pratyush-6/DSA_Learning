<?php
/**
 * PDO database connection (singleton).
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Returns a shared PDO connection to the application database.
 *
 * @param bool $withDb When false, connects to the server without selecting a
 *                     database (used by setup.php before the DB exists).
 */
function db(bool $withDb = true): PDO
{
    static $instances = [];

    $key = $withDb ? 'main' : 'server';
    if (isset($instances[$key])) {
        return $instances[$key];
    }

    $dsn = sprintf('mysql:host=%s;port=%s;charset=%s', DB_HOST, DB_PORT, DB_CHARSET);
    if ($withDb) {
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_NAME, DB_CHARSET);
    }

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    $instances[$key] = $pdo;
    return $pdo;
}
