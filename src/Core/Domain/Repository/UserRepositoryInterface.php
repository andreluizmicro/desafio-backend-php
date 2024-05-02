<?php

declare(strict_types=1);

namespace Core\Domain\Repository;

use Core\Domain\Entity\User;
use Core\Domain\Exception\UserException;
use Core\Domain\ValueObject\Id;

interface UserRepositoryInterface
{
    public function create(User $user): int;

    /**
     * @throws UserException
     */
    public function findById(Id $id): ?User;
}
