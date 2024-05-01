<?php

declare(strict_types=1);

namespace Core\Infrastructure\Listener;

use Core\Domain\Notification\NotificationQueueInterface;
use Core\Infrastructure\Event\TransferEvent;

readonly class SendTransferNotification
{
    public function __construct(
        private NotificationQueueInterface $notificationQueue
    ) {
    }

    public function handle(TransferEvent $event): void
    {
        $this->notificationQueue->produce(
            payload: $event->getPayload(),
            exchange: config('transfer_ms.rabbitmq.exchange'),
        );
    }
}
