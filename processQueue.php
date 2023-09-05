<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\MariaDb;
use App\QueueManager;
use App\Enums\Channel;
use function App\getContentLength;
use function App\insertToClickHouse;
use function App\makeMariaDbQuery;
use PhpAmqpLib\Message\AMQPMessage;

$queue = new QueueManager();


function insertInMariaDB(string $body, int $contentLength)
{

}

$parseUrl = function (AMQPMessage $msg) {

    $url = $msg->body;
    try {
        echo 'Определяем длину контента для ' . $url . '... ';
        $contentLength = getContentLength($url);
    } catch (Exception $e) {
        echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;;
        return;
    }

    echo 'Успех: длина = ' . $contentLength . ' байт' . PHP_EOL;

    // добавляем в базу
    insertInMariaDB($msg->body, $contentLength);
    insertToClickHouse($msg->body, $contentLength);
};


$makeSqlQuery = function (AMQPMessage $msg) {
    makeMariaDbQuery();
};

$queue->listen(Channel::PARSE_URL, $parseUrl);

$queue->listen(Channel::SQL_QUERY, $makeSqlQuery);

$queue->awaitLoop();

$queue->closeConnection();
