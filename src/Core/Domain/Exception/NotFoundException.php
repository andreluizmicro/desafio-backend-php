<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class NotFoundException extends DomainException
{
    public static function notFound(string $message): self
    {
        return new self(
            $message,
            404
        );
    }
}
