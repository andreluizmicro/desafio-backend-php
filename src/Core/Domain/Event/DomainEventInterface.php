<?php

declare(strict_types=1);

namespace Core\Domain\Event;

interface DomainEventInterface
{
    public function getEventName(): string;

    public function getPayload(): array;
}
