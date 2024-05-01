<?php

declare(strict_types=1);

namespace Core\Application\Transfer\Create;

use Core\Domain\Adapter\UnitOfWorkAdapterInterface;
use Core\Domain\Entity\Account;
use Core\Domain\Entity\Transfer;
use Core\Domain\Exception\AccountException;
use Core\Domain\Exception\TransferException;
use Core\Domain\Gateway\AuthorizationGatewayInterface;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\Repository\TransferRepositoryInterface;
use Core\Domain\ValueObject\Id;
use Core\Infrastructure\Event\TransferCreated;
use Shared\Domain\Adapter\EventAdapterInterface;
use Throwable;

readonly class CreateTransferService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TransferRepositoryInterface $transferRepository,
        private AuthorizationGatewayInterface $authorizationGateway,
        private UnitOfWorkAdapterInterface $unitOfWorkAdapter,
        private EventAdapterInterface $eventAdapter,
    ) {
    }

    /**
     * @throws TransferException
     * @throws AccountException
     */
    public function execute(Input $input): Output
    {
        if ($this->isInvalidTransaction($input)) {
            throw AccountException::invalidTransaction();
        }

        try {
            $payer = $this->accountRepository->findById(new Id($input->payerId));
            $payee = $this->accountRepository->findById(new Id($input->payeeId));

            $transfer = new Transfer(
                value: $input->value,
                payer: $payer,
                payee: $payee,
            );

            $this->authorizationGateway->authorizeTransfer();

            $this->unitOfWorkAdapter->begin();

            $transfer->setId($this->transferRepository->create($transfer));

            $this->updateUsersBalance($payer, $payee);

            $this->unitOfWorkAdapter->commit();

            $output = new Output($transfer->getId());

        } catch (Throwable $th) {
            $this->unitOfWorkAdapter->rollback();
            throw TransferException::transferAuthorizedError($th->getMessage());
        }

        $this->eventAdapter->publish(
            new TransferCreated(
                transfer: $transfer
            )
        );

        return $output;
    }

    private function updateUsersBalance(Account $payer, Account $payee): void
    {
        $this->accountRepository->updateUserBalance($payer);
        $this->accountRepository->updateUserBalance($payee);
    }

    private function isInvalidTransaction(Input $input): bool
    {
        return $input->payerId === $input->payeeId;
    }
}
