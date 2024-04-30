<?php

declare(strict_types=1);

namespace Core\Domain\ValueObject;

use Core\Domain\Exception\IdException;

class Id
{
    private const INVALID_ID = 0;

    /**
     * @throws IdException
     */
    public function __construct(
        public int $value,
    ) {
        $this->validate($this->value);
    }

    /**
     * @throws IdException
     */
    private function validate(int $id): void
    {
        if ($this->value <= self::INVALID_ID) {
            throw IdException::invalidId($id);
        }
    }
}
