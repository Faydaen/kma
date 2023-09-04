<?php

use function App\getContentLength;

require_once __DIR__ . '/vendor/autoload.php';


$a = \App\MariaDb::query("SELECT * FROM parse_results");
var_dump($a);
//// Пример использования
//try {
//    $url = "https://www.example.com";
//    $length = getContentLength($url);
//    echo "Длина контента по URL $url: $length байтов";
//} catch (Exception $e) {
//    echo "Ошибка: " . $e->getMessage();
//}

//$myVar = getenv('CLICKHOUSE_USER');
//echo $myVar;
//die();
//
//var_dump($_ENV);
//die();

//$db = new \App\ClickHouse();
//$a=$db->query("SELECT * FROM parse_results");
//var_dump($a);
//try {
//    $pdo = new PDO('clickhouse:host=clickhouse;port=8123;dbname=default', 'username', 'my_password');
//    // Укажите адрес и порт ClickHouse, а также имя базы данных, пользователя и пароль
//
//    $query = "SELECT * FROM your_table";
//    $stmt = $pdo->prepare($query);
//    $stmt->execute();
//
//    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//        print_r($row);
//    }
//} catch (PDOException $e) {
//    echo "Ошибка подключения к ClickHouse: " . $e->getMessage();
//}




