<?php

declare(strict_types=1);

namespace Core\Domain\Entity;

use Core\Domain\Enum\UserType;
use Core\Domain\Exception\AccountException;
use Core\Domain\Exception\IdException;
use Core\Domain\ValueObject\Id;

class Transfer
{
    /**
     * @throws AccountException
     */
    public function __construct(
        private readonly float $value,
        private readonly Account $payer,
        private readonly Account $payee,
        private ?Id $id = null,
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
        return $this->payer->user->getUserType() === UserType::shopkeeperUser->value;
    }

    /**
     * @throws IdException
     */
    public function setId(int $value): void
    {
        $this->id = new Id($value);
    }

    public function getId(): ?int
    {
        return $this->id->value ?? null;
    }

    public function getPayer(): Account
    {
        return $this->payer;
    }

    public function getPayee(): Account
    {
        return $this->payee;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value ?? null,
            'payer_id' => $this->payer->getId()->value,
            'payee_id' => $this->payee->getId()->value,
            'value' => $this->value,
        ];
    }
}
