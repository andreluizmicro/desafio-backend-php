<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class NameException extends UserException
{
    public static function invalidName(string $name): self
    {
        return new self(
            sprintf('The name %s is invalid', $name)
        );
    }
}
