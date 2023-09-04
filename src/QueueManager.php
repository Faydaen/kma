<?php

namespace App;

use Exception;
use App\Enums\Channel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class QueueManager
{
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
            $user = getenv('RABBITMQ_DEFAULT_USER');
            $password = getenv('RABBITMQ_DEFAULT_PASS');
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

    public function listen(Channel $chanel, callable $callback): void
    {
        // получаем/создаём очередь с нужным ключом
        $this->channel->queue_declare($chanel->value, false, false, false, false);

        // передаём туда callback
        $this->channel->basic_consume($chanel->value, '', false, true, false, false, $callback);
    }

    public function awaitLoop() : void
    {
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
