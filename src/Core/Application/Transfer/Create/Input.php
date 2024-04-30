<?php

declare(strict_types=1);

namespace Core\Application\Transfer\Create;

class Input
{
    public function __construct(
        public float $value,
        public int $payerId,
        public int $payeeId,
    ) {
    }
}
