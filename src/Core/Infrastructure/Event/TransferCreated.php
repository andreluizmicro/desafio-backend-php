<?php

declare(strict_types=1);

namespace Core\Infrastructure\Event;

use Core\Domain\Entity\Transfer;

class TransferCreated extends TransferEvent
{
    public function __construct(
        private readonly Transfer $transfer
    ) {
    }

    private const EVENT_NAME = 'transfer.created';

    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    public function getPayload(): array
    {
        return $this->transfer->toArray();
    }
}
