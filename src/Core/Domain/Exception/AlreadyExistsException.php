<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class AlreadyExistsException extends DomainException
{
    public static function alreadyExists(string $message): self
    {
        return new self(
            $message,
            409
        );
    }
}
