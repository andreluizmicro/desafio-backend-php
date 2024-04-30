<?php

declare(strict_types=1);

namespace Core\Domain\ValueObject;

use Core\Domain\Exception\EmailException;

final readonly class Email
{
    /**
     * @throws EmailException
     */
    public function __construct(public string $value)
    {
        $this->validate();
    }

    /**
     * @throws EmailException
     */
    private function validate(): void
    {
        if (! filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw EmailException::invalidEmail($this->value);
        }
    }
}
