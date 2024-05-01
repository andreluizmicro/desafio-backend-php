<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Queue\RabbitMQ;

use Core\Domain\Notification\NotificationQueueInterface;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class NotificationQueue implements NotificationQueueInterface
{
    protected $connection = null;

    protected $channel = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if ($this->connection) {
            return;
        }
        $configs = config('transfer_ms.rabbitmq.hosts')[0];

        $this->connection = new AMQPStreamConnection(
            host: $configs['host'],
            port: $configs['port'],
            user: $configs['user'],
            password: $configs['password'],
            vhost: $configs['vhost'],
        );

        $this->channel = $this->connection->channel();
    }

    /**
     * @throws Exception
     */
    public function produce(array $payload, string $exchange): void
    {
        $this->channel->exchange_declare(
            exchange: $exchange,
            type: AMQPExchangeType::FANOUT,
            durable: true,
            auto_delete: false
        );

        $message = new AMQPMessage(json_encode($payload), [
            'Content_type' => 'application/json',
        ]);

        $this->channel->basic_publish($message, $exchange);
        $this->closeChannel();
        $this->closeConnection();
    }

    private function closeChannel(): void
    {
        $this->channel->close();
    }

    /**
     * @throws Exception
     */
    private function closeConnection(): void
    {
        $this->connection->close();
    }
}
