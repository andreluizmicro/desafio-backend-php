<?php

declare(strict_types=1);

namespace Core\Application\Interfaces;

interface DBTransactionInterface
{
    public function commit(): void;

    public function rollback(): void;
}
