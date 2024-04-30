<?php

declare(strict_types=1);

namespace Core\Domain\Repository;

use Core\Domain\Entity\Account;
use Core\Domain\Exception\AlreadyExistsException;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\ValueObject\Id;

interface AccountRepositoryInterface
{
    /**
     * @throws AlreadyExistsException
     */
    public function create(Account $account): int;

    /**
     * @throws NotFoundException
     */
    public function findById(Id $id): Account;

    public function updateUserBalance(Account $account): void;

    public function existsById(Id $id): bool;
}
