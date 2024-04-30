<?php

declare(strict_types=1);

namespace Core\Domain\ValueObject;

use Core\Domain\Exception\PasswordException;

final class Password
{
    private const MIN_LENGTH = 8;

    /**
     * @throws PasswordException
     */
    public function __construct(public string $value)
    {
        $this->validate();
        $this->value = $this->hash($this->value);
    }

    /**
     * @throws PasswordException
     */
    private function validate(): void
    {
        if (! $this->isValid()) {
            throw PasswordException::invalidPassword($this->value);
        }
    }

    private function isValid(): bool
    {
        return strlen($this->value) >= self::MIN_LENGTH && preg_match('/(?=.*[a-z])(?=.*[A-Z])/', $this->value);
    }

    public function hash(string $rawPassword): string
    {
        return password_hash($rawPassword, PASSWORD_DEFAULT);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
