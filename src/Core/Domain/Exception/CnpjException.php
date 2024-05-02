<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class CnpjException extends UserException
{
    public static function invalidCnpj(string $number): self
    {
        return new self(
            sprintf('The cnpj %s is invalid.', $number),
            422
        );
    }
}
