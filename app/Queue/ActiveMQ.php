<?php

namespace App\Queue;

use Exception;
use Illuminate\Support\Facades\App;
use Stomp\Client;
use Stomp\Transport\Map;

class ActiveMQ
{
    protected static Client $client;

    public const DELAY = 'AMQ_SCHEDULED_DELAY';
    public const PERIOD = 'AMQ_SCHEDULED_PERIOD';
    public const REPEAT = 'AMQ_SCHEDULED_REPEAT';
    public const CRON = 'AMQ_SCHEDULED_CRON';

    protected const READ_TIMEOUT_SECONDS = 0;

    //microseconds (1μs = 0.000001s, ex. 500ms = 500000)
    protected const READ_TIMEOUT_MICROSECONDS = 250000;

    public static function get(): Client
    {
        if (!isset(static::$client)) {
            static::$client = new Client(
                config('queue.connections.activemq.broker')
            );
            static::$client->setLogin(
                config('queue.connections.activemq.username'),
                config('queue.connections.activemq.password')
            );
            static::$client->getConnection()
                ->setReadTimeout(static::READ_TIMEOUT_SECONDS, static::READ_TIMEOUT_MICROSECONDS);
        }

        return static::$client;
    }

    /**
     * @param string $queue
     * @param array $msg
     * @param array $header
     * https://activemq.apache.org/delay-and-schedule-message-delivery
     * AMQ_SCHEDULED_DELAY    long    The time in milliseconds that a message will wait before being scheduled to be delivered by the broker
     * AMQ_SCHEDULED_PERIOD    long    The time in milliseconds to wait after the start time to wait before scheduling the message again
     * AMQ_SCHEDULED_REPEAT    int    The number of times to repeat scheduling a message for delivery
     * AMQ_SCHEDULED_CRON    String    Use a Cron entry to set the schedule
     *
     * @return bool
     *
     */
    public static function send(string $queue, array $msg, array $header = []): bool
    {
        if (App::environment('testing')) {
            return true;
        }

        try {
            static::get()
                ->send(
                    sprintf('/queue/%s', $queue),
                    new Map($msg),
                    $header
                );
        } catch (Exception $e) {
            logger(static::class.'::send', ['error' => $e->getMessage()]);

            return false;
        }

        return true;
    }
}
