<?php

declare(strict_types=1);

namespace Core\Application\Account\Create;

class Output
{
    public function __construct(
        public int $accountId,
    ) {
    }
}
