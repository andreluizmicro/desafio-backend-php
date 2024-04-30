<?php

namespace Core\Domain\Exception;

class CpfException extends DomainException
{
    public static function invalidCpf(string $number): self
    {
        return new self(
            sprintf('The cpf %s is invalid.', $number)
        );
    }
}
