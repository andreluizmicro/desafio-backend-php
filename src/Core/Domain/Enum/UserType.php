<?php

declare(strict_types=1);

namespace Core\Domain\Enum;

use InvalidArgumentException;

enum UserType: int
{
    case defaultUser = 1;
    case shopkeeperUser = 2;

    public static function fromInt(int $user_type_id): UserType
    {
        return match ($user_type_id) {
            1 => self::defaultUser,
            2 => self::shopkeeperUser,
            default => throw new InvalidArgumentException("Invalid user type ID: $user_type_id"),
        };
    }
}
