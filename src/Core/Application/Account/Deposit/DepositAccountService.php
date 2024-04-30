<?php

declare(strict_types=1);

namespace Core\Application\Account\Deposit;

use Core\Domain\Exception\AccountException;
use Core\Domain\Exception\IdException;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\ValueObject\Id;

class DepositAccountService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    /**
     * @throws IdException
     * @throws AccountException
     * @throws NotFoundException
     */
    public function execute(Input $input): Output
    {
        $account = $this->accountRepository->findById(new Id($input->accountId));

        $account->creditAccount($input->value);
        $this->accountRepository->updateUserBalance($account);

        return new Output(
            success: true
        );
    }
}
