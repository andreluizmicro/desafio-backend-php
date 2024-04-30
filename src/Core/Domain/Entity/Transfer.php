<?php

declare(strict_types=1);

namespace Core\Domain\Entity;

use Core\Domain\Enum\UserType;
use Core\Domain\Exception\AccountException;
use Core\Domain\ValueObject\Id;

class Transfer
{
    /**
     * @throws AccountException
     */
    public function __construct(
        public float $value,
        public Account $payer,
        public Account $payee,
        public ?Id $id = null,
    ) {
        $this->id = $this->id ?? null;
        $this->validate();
        $this->makeTransfer();
    }

    /**
     * @throws AccountException
     */
    private function makeTransfer(): void
    {
        $this->payer->debitAccount($this->value);
        $this->payee->creditAccount($this->value);
    }

    /**
     * @throws AccountException
     */
    private function validate(): void
    {
        if ($this->isInvalidPayer()) {
            throw AccountException::invalidPayer();
        }
    }

    private function isInvalidPayer(): bool
    {
        return $this->payer->user->userType->value === UserType::shopkeeperUser->value;
    }
}
