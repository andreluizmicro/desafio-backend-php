<?php

declare(strict_types=1);

namespace Core\Domain\Adapter;

interface UnitOfWorkAdapterInterface
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
