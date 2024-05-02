<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class CpfException extends UserException
{
    public static function invalidCpf(string $number): self
    {
        return new self(
            sprintf('The cpf %s is invalid.', $number),
            422
        );
    }
}
