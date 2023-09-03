<?php

namespace App;

use PDO;
use Exception;
use PDOException;

class Repository
{
    public static Repository $instance;
    private static PDO $pdo;
    private function __construct()
    {
    }
    private function __clone()
    {
    }

    private static function connect(): void
    {
        try {
            // конфигурацию подключения либо находится в переменных окружения либо указаны в docker-compose.yaml
            $host = 'mariadb';
            $port = '3306';
            $username = env('MARIADB_USERNAME');
            $password = env('MARIADB_PASSWORD');
            $database = env('MARIADB_DATABASE');

            var_dump($port);
            // подключаемся к базе данных
            self::$pdo = new PDO("mysql:host=$host:$port;dbname=$database", $username, $password);

            // просим получать результаты в формате ассоциативного массива
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance(): Repository
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
            self::$instance::connect();
        }

        return self::$instance;
    }

    public static function query(string $query) : array {
        try {
            $result = self::$pdo->query($query);
            return $result->fetchAll();
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
    }
}
