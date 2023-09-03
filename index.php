<?php


// убираем ограничение по времени
set_time_limit(0);

const MIN_SECONDS = 5;
const MAX_SECONDS = 30;

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

echo "start<br>".PHP_EOL;
foreach ($urls as $url){

    $delay = mt_rand(MIN_SECONDS, MAX_SECONDS);
    send($url, $delay);
    sleep($delay);
}

echo "end";

function send($url, $delay){
echo (new DateTime())->format('c');
echo ' '.$url.'now will await for '.$delay.' seconds<br>'.PHP_EOL;
}





//
//use Async\Coroutine;
//
//// Создаем асинхронную курутину
//$coroutine = Coroutine::of(function () {
//    echo "Начало курутины\n";
//
//    // Подождем 1 секунду без блокировки основного потока
//    await(sleep(1));
//
//    echo "Завершение курутины\n";
//});
//
//// Запускаем курутину
//$coroutine->run();
//
//echo "Основной поток\n";

//require_once __DIR__ . '/vendor/autoload.php';
//use PhpAmqpLib\Connection\AMQPStreamConnection;
//use PhpAmqpLib\Message\AMQPMessage;
//
//
//
//
//$urls = [
//    'https://kma.biz/',
//    'https://github.com/',
//    'https://hh.ru/',
//    'https://www.youtube.com/',
//    'https://www.instagram.com/',
//    'https://habr.com/',
//    'https://ru.wikipedia.org/',
//    'https://www.docker.com/',
//    'https://huggingface.co/',
//    'https://www.google.com/',
//    'https://swagger.io/',
//];
//
//$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
//$channel = $connection->channel();
