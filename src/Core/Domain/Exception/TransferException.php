<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class TransferException extends DomainException
{
    public static function transferAuthorizedError(string $message): self
    {
        return new self(
            $message,
            403
        );
    }

    public static function transferError(): self
    {
        return new self(
            'An error occurred while trying to make the transfer',
            400
        );
    }
}
