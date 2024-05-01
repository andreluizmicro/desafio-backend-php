<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class AccountException extends DomainException
{
    public static function accountAlreadyExists(): self
    {
        return new self(
            'This user already has an account',
            409,
        );
    }

    public static function insufficientBalance(): self
    {
        return new self(
            'Insufficient balance',
            403
        );
    }

    public static function invalidBalanceToCreateAccount(): self
    {
        return new self('it is not possible to create an account with a negative balance');
    }

    public static function invalidCreditValue(): self
    {
        return new self('credit value cant be zero');
    }

    public static function invalidPayer(): self
    {
        return new self(
            'shopkeeper cant make a transfer',
            401
        );
    }

    public static function invalidTransaction(): self
    {
        return new self(
            'it is not possible to make payment yourself',
            400
        );
    }
}
