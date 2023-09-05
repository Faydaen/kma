<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Enums\Channel;
use App\QueueManager;

// убираем ограничение по времени
set_time_limit(0);

const MIN_SECONDS = 5;
const MAX_SECONDS = 30;

$urls = [
    'https://github.com/',
    'https://laravel.com/',
    'https://hh.ru/',
    'https://www.youtube.com/',
    'https://mail.ru/',
    'https://habr.com/',
    'https://www.britannica.com/',
    'https://huggingface.co/',
    'https://clickhouse.com/',
    'https://swagger.io/',
    'https://tproger.ru/',
];

$queue = new QueueManager();
foreach ($urls as $url) {

    $queue->sendMessage($url, Channel::PARSE_URL);

    // если есть следующий урл в массиве, то делам задержку
    $delay = mt_rand(MIN_SECONDS, MAX_SECONDS);
    echo 'Добавляем в очередь ' . $url . '. следующие добавление через ' . $delay. ' секунд'.PHP_EOL;
    if (!next($urls)) {
        sleep($delay);
    }
}

// это сообщение будет обозначать что нужно сделать запрос в базу данных для выборки
$queue->sendMessage('', Channel::SQL_QUERY);

$queue->closeConnection();
