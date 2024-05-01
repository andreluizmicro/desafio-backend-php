<?php

declare(strict_types=1);

namespace Core\Infrastructure\Adapter;

use Core\Domain\Event\DomainEventInterface;
use Shared\Domain\Adapter\EventAdapterInterface;

class LaravelEventAdapter implements EventAdapterInterface
{
    public function publish(DomainEventInterface $event): void
    {
        event($event);
    }
}
