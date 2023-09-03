<?php

namespace App;

use PDO;
use Exception;
use PDOException;

class Repository
{
    public static Repository $instance;
    private static PDO $pdo;
    protected function __construct()
    {
    }
    protected function __clone()
    {
    }

    private static function init(): void
    {
        try {
            // конфигурацию подключения берем из переменных окружения
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $database = env('DB_DATABASE');

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
            self::$instance::init();
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
