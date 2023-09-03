<?php

namespace App;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
class Queue
{
    private AMQPStreamConnection $connection;

    public function __construct()
    {
        try {
            $host = 'rabbitmq';
            $port = '5672';
            $user = env('RABBITMQ_DEFAULT_USER');
            $password = env('RABBITMQ_DEFAULT_PASS');
            $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
        } catch (Exception $e) {
            die($e->getMessage());

        }

    }

    public function test(){

    }

}
