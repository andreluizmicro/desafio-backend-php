<?php

declare(strict_types=1);

namespace Core\Application\Account\Deposit;

class Output
{
    public function __construct(
        public bool $success
    ) {
    }
}
