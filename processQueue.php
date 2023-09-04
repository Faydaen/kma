<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\MariaDb;
use App\QueueManager;
use App\Enums\Channel;
use function App\getContentLength;
use PhpAmqpLib\Message\AMQPMessage;

$queue = new QueueManager();


$parseUrl = function (AMQPMessage $msg) {

    $url = $msg->body;
    try {
        echo 'Определяем длину контента для ' . $url . PHP_EOL;
        $contentLength = getContentLength($url);
    } catch (Exception $e) {
        echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
        return;
    }

    echo 'Длина = ' . $contentLength . ' байт' . PHP_EOL;;

    // делаем запрос
    $sql = 'INSERT INTO parse_results (url, content_length) VALUES (:url, :content_length)';
    $params = [
        'url' => $msg->body,
        'content_length' => $contentLength
    ];
    MariaDb::query($sql, $params);
    echo $msg->body;

    echo PHP_EOL;
};


$makeSqlQuery = function (AMQPMessage $msg) {
    echo 'end ';
    echo PHP_EOL;
};

$queue->listen(Channel::PARSE_URL, $parseUrl);

$queue->listen(Channel::SQL_QUERY, $makeSqlQuery);

$queue->awaitLoop();

$queue->closeConnection();
