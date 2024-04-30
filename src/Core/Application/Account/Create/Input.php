<?php

declare(strict_types=1);

namespace Core\Application\Account\Create;

class Input
{
    public function __construct(
        public int $userId
    ) {
    }
}
