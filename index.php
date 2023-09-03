<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$urls = [
    'https://kma.biz/',
    'https://github.com/',
    'https://hh.ru/',
    'https://www.youtube.com/',
    'https://www.instagram.com/',
    'https://habr.com/',
    'https://ru.wikipedia.org/',
    'https://www.docker.com/',
    'https://huggingface.co/',
    'https://www.google.com/',
    'https://swagger.io/',
];

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
