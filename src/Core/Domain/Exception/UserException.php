<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

class UserException extends DomainException
{
    public static function userNotFound(int $id): self
    {
        return new self(
            sprintf('user with id %d not found', $id)
        );
    }

    public static function userAlreadyExists(): self
    {
        return new self('User Already exists');
    }
}
