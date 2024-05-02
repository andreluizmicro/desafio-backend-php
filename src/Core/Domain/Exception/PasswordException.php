<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class PasswordException extends UserException
{
    public static function invalidPassword(string $value): self
    {
        return new self(
            sprintf('The password %s is invalid', $value),
            422
        );
    }
}
