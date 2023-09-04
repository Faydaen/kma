<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Enums\Channel;
use App\QueueManager;
use PhpAmqpLib\Message\AMQPMessage;
use function App\getContentLength;

$queue = new QueueManager();


$parseUrl = function (AMQPMessage $msg) {

//    $url = $msg->body;
////    $contentLength = 0;
//    try {
//        $contentLength = getContentLength($url);
//    } catch (Exception $e) {
//        echo "Ошибка: " . $e->getMessage();
//    }
//
//    \App\MariaDb::query("INSERT INTO  (url, content_length) VALUES (value1, value2, value3,...valueN);");

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
