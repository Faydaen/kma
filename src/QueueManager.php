<?php

namespace App;

use App\enums\Channel;
use Exception;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueManager
{
    const ROUTING_KEY = 'urls_to_parse';

    private array $eventHandlers = [];
    private AMQPChannel $channel;
    private AMQPStreamConnection $connection;

    public function __construct()
    {
        $this->connect();
    }

    public function connect(): void
    {
        try {
            $host = 'rabbitmq';
            $port = '5672';
            $user = env('RABBITMQ_DEFAULT_USER');
            $password = env('RABBITMQ_DEFAULT_PASS');
            $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
            // подключение будет одноканальным
            $this->channel = $this->connection->channel();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function sendMessage(string $message, Channel $chanel): void
    {
        // получаем очередь с заданным routing key (если её нет то создаём её)
        $this->channel->queue_declare($chanel->value, false, false, false, false);

        // создаем сообщение и кладём его в exchange с заданным routing key
        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, '', $chanel->value);
    }



    public function listen(Channel $chanel, callable $callback)
    {
        // получаем/создаём очередь с нужным ключом
        $this->channel->queue_declare($chanel->value, false, false, false, false);

        $this->channel->basic_consume($chanel->value, '', false, true, false, false, $callback);

//        while (count($this->channel->callbacks)) {
//            $this->channel->wait();
//        }
//
////        while ($this->channel->is_open()) {
////            $this->channel->wait();
////        }
////
////        $this->channel->close();
////        $this->connection->close();
//
//        echo '::finish::'.PHP_EOL;

//

    }

    public function awaitLoop(){
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

    }


    public function closeConnection(): void
    {
        $this->channel->close();
        try {
            $this->connection->close();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

}
