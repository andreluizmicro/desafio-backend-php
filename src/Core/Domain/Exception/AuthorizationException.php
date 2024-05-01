<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class AuthorizationException extends DomainException
{
    public static function unauthorized(string $message): self
    {
        return new self(
            $message,
            401
        );
    }
}
