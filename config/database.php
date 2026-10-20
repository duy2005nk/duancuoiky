<?php

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?PDO $connection = null;

    private function __construct()
    {
    }

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '5432';
        $dbname = getenv('DB_NAME') ?: 'trinhvanhung_b2b';
        $username = getenv('DB_USER') ?: 'postgres';
        $password = getenv('DB_PASS') ?: 'secretpassword';
        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";

        try {
            self::$connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException('Cannot connect to the database.', 0, $exception);
        }

        return self::$connection;
    }

    public static function getConnection(): PDO
    {
        return self::connection();
    }
}
