<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class EmailException extends UserException
{
    public static function invalidEmail(string $email): self
    {
        return new self(
            sprintf('The email %s is invalid.', $email),
            422
        );
    }
}
