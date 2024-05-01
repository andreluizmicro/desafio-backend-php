<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class IdException extends DomainException
{
    public static function invalidId(int $number): self
    {
        return new self(
            sprintf('The id %s is invalid.', $number),
            422
        );
    }
}
