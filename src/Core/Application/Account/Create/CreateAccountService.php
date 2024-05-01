<?php

declare(strict_types=1);

namespace Core\Application\Account\Create;

use Core\Domain\Entity\Account;
use Core\Domain\Entity\User;
use Core\Domain\Exception\AccountException;
use Core\Domain\Exception\AlreadyExistsException;
use Core\Domain\Exception\IdException;
use Core\Domain\Exception\UserException;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\Repository\UserRepositoryInterface;
use Core\Domain\ValueObject\Id;

readonly class CreateAccountService
{
    private const DEFAULT_BALANCE = 0;

    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @throws AccountException
     * @throws UserException
     * @throws IdException
     * @throws AlreadyExistsException
     */
    public function execute(Input $input): Output
    {
        $userId = new Id($input->userId);

        if ($this->accountExists($userId)) {
            throw AccountException::accountAlreadyExists();
        }

        $user = $this->findUserById($userId);

        $account = new Account(
            user: $user,
            balance: self::DEFAULT_BALANCE,
        );

        $accountCreatedId = $this->accountRepository->create($account);

        return new Output(
            accountId: $accountCreatedId
        );
    }

    private function accountExists(Id $id): bool
    {
        return $this->accountRepository->existsById($id);
    }

    /**
     * @throws UserException
     */
    private function findUserById(Id $id): User
    {
        return $this->userRepository->findById($id);
    }
}
