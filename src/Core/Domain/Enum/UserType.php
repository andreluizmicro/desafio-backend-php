<?php

declare(strict_types=1);

namespace Core\Domain\Enum;

enum UserType: int
{
    case defaultUser = 1;
    case shopkeeperUser = 2;

    public function label(): string
    {
        return match ($this) {
            UserType::defaultUser => 'Comum',
            UserType::shopkeeperUser => 'Lojista',
        };
    }
}
