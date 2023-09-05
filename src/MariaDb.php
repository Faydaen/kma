<?php

namespace App;

use PDO;
use Exception;
use PDOException;

class MariaDb
{

    public static MariaDb $instance;
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
            $database = 'db';
            $username = getenv('MARIADB_USERNAME');
            $password = getenv('MARIADB_PASSWORD');

            // подключаемся к базе данных
            self::$pdo = new PDO("mysql:host=$host:$port;dbname=$database", $username, $password);

            // просим получать результаты в формате ассоциативного массива
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Ошибка подключения к базе данных: ' . $e->getMessage());
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance(): MariaDb
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
            self::$instance::connect();
        }

        return self::$instance;
    }

    public static function insert(string $query, array $params): void
    {
        try {
            self::getInstance()::$pdo->prepare($query)->execute($params);
        } catch (PDOException $e) {
            die('Ошибка выполнения запроса: ' . $e->getMessage());
        }
    }

    public static function query(string $query, array $params = []): array
    {
        try {
            $result = self::getInstance()::$pdo->query($query);
            return $result->fetchAll();
        } catch (PDOException $e) {
            die('Ошибка выполнения запроса: ' . $e->getMessage());
        }
    }
}
