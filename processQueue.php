<?php

use App\enums\Channel;
use App\QueueManager;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/vendor/autoload.php';

$queue = new QueueManager();


$parseUrl = function (AMQPMessage $msg) {
    echo get_class($msg);
    echo ' ';
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