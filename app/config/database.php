<?php
namespace App\Config;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use PDO;
use PDOException;

class Database
{
    private static $pdo;

    public static function connection()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $host     = $_ENV['DB_HOST'];
        $dbname   = $_ENV['DB_DATABASE'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        try {
            self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo 'Done';
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
        return self::$pdo;
    }
}

$test = new Database;
$test::connection();
