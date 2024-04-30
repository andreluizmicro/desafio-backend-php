<?php

declare(strict_types=1);

namespace Core\Domain\ValueObject;

use Core\Domain\Exception\NameException;

final readonly class Name
{
    private const MIN_LENGTH = 3;

    private const MAX_LENGTH = 255;

    /**
     * @throws NameException
     */
    public function __construct(public string $value)
    {
        $this->validate();
    }

    /**
     * @throws NameException
     */
    private function validate(): void
    {
        if (strlen($this->value) < self::MIN_LENGTH || strlen($this->value) > self::MAX_LENGTH) {
            throw NameException::invalidName($this->value);
        }
    }
}
