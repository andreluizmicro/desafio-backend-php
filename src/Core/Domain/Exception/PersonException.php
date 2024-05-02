<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class PersonException extends DomainException
{
    public static function invalidNaturalPerson(): self
    {
        return new self(
            'natural person does not have a CNPJ',
            422
        );
    }

    public static function invalidLegalPerson(): self
    {
        return new self(
            'legal entity must have a CNPJ',
            422
        );
    }
}
