<?php

declare(strict_types=1);

namespace Core\Application\Transfer\Create;

use Core\Domain\Adapter\UnitOfWorkAdapterInterface;
use Core\Domain\Entity\Account;
use Core\Domain\Entity\Transfer;
use Core\Domain\Exception\TransferException;
use Core\Domain\Gateway\AuthorizationGatewayInterface;
use Core\Domain\Gateway\NotificationGatewayInterface;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\Repository\TransferRepositoryInterface;
use Core\Domain\ValueObject\Id;
use Throwable;

class CreateTransferService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TransferRepositoryInterface $transferRepository,
        private AuthorizationGatewayInterface $authorizationGateway,
        private NotificationGatewayInterface $notificationGateway,
        private UnitOfWorkAdapterInterface $unitOfWorkAdapter,
    ) {
    }

    /**
     * @throws TransferException
     */
    public function execute(Input $input): Output
    {
        try {
            $payer = $this->accountRepository->findByUserId(new Id($input->payerId));
            $payee = $this->accountRepository->findByUserId(new Id($input->payeeId));

            $transfer = new Transfer(
                value: $input->value,
                payer: $payer,
                payee: $payee,
            );

            $this->authorizationGateway->authorizeTransfer();

            $this->unitOfWorkAdapter->begin();

            $transferId = $this->transferRepository->create($transfer);

            $this->updateUsersBalance($payer, $payee);

            $this->notificationGateway->notify();

            $this->unitOfWorkAdapter->commit();

            return new Output(
                transferId: $transferId,
            );

        } catch (Throwable $th) {
            $this->unitOfWorkAdapter->rollback();
            throw TransferException::transferAuthorizedError($th->getMessage());
        }
    }

    private function updateUsersBalance(Account $payer, Account $payee): void
    {
        $this->accountRepository->updateUserBalance($payer);
        $this->accountRepository->updateUserBalance($payee);
    }
}
