<?php

declare(strict_types=1);

namespace Core\Application\Account\Deposit;

class Input
{
    public function __construct(
        public int $accountId,
        public float $value,
    ) {
    }
}
