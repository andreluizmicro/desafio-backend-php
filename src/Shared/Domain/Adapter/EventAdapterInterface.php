<?php

declare(strict_types=1);

namespace Shared\Domain\Adapter;

use Core\Domain\Event\DomainEventInterface;

interface EventAdapterInterface
{
    public function publish(DomainEventInterface $event): void;
}
