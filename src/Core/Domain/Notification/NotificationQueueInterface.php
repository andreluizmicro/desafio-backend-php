<?php

declare(strict_types=1);

namespace Core\Domain\Notification;

interface NotificationQueueInterface
{
    public function produce(array $payload, string $exchange): void;
}
