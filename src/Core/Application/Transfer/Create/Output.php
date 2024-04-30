<?php

declare(strict_types=1);

namespace Core\Application\Transfer\Create;

class Output
{
    public function __construct(
        public int $transferId
    ) {
    }
}
