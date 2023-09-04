<?php

namespace App;

use PDO;
use Exception;
use PDOException;

class ClickHouse
{
    private PDO $pdo;
    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        try {
            // конфигурацию подключения либо находится в переменных окружения либо указаны в docker-compose.yaml
            $host = 'clickhouse';
            $port = '3306';
            $username = getenv('CLICKHOUSE_USER');
            $password = getenv('CLICKHOUSE_PASSWORD');
            $database = getenv('CLICKHOUSE_DB');

            // подключаемся к базе данных
            $this->pdo = new PDO("clickhouse:host=$host:$port;dbname=$database", $username, $password);

            // просим получать результаты в формате ассоциативного массива
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function query(string $query) : array {
        try {
            $result = $this->pdo->query($query);
            return $result->fetchAll();
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
    }
}
