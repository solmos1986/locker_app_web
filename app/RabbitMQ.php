<?php
namespace App;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ
{
    public function __construct()
    {}

    public function publish($message)
    {
        Log::info("RabbitMQ publish " . jsonLog($message));
        $connection = new AMQPStreamConnection(env('MQ_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PASS'), env('MQ_VHOST'));
        $channel    = $connection->channel();
        //$channel->exchange_declare('test_exchange', 'direct', false, false, false);
        $channel->queue_declare('open', false, false, false, false);
        //$channel->queue_bind('hello', 'test_exchange', 'test_key');
        $msg = new AMQPMessage($message, [
            'content_type'  => 'application/json',
            //'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT, // Optional: make message persistent
        ]);
        $channel->basic_publish($msg, '', 'open');
        Log::info("RabbitMQ [x] Sent $message to test_exchange / test_queue.\n");

        $channel->close();
        $connection->close();
    }

    public function consume()
    {
        $connection = new AMQPStreamConnection(env('MQ_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PASS'), env('MQ_VHOST'));
        $channel    = $connection->channel();
        $callback   = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };
        $channel->queue_declare('test_queue', false, false, false, false);
        $channel->basic_consume('test_queue', '', false, true, false, false, $callback);
        echo 'Waiting for new message on test_queue', " \n";
        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }
}
