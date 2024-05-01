<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class TransferException extends DomainException
{
    public static function transferAuthorizedError(string $message): self
    {
        return new self($message);
    }
}
