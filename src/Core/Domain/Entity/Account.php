<?php

declare(strict_types=1);

namespace Core\Domain\Entity;

use Core\Domain\Exception\AccountException;
use Core\Domain\Exception\IdException;
use Core\Domain\ValueObject\Id;

class Account
{
    private const MIN_BALANCE = 0;

    /**
     * @throws AccountException
     */
    public function __construct(
        public readonly User $user,
        private ?float $balance = self::MIN_BALANCE,
        private ?Id $id = null,
    ) {
        $this->id = $this->id ?? null;
        $this->validate();
    }

    /**
     * @throws AccountException
     */
    private function validate(): void
    {
        if ($this->balance < self::MIN_BALANCE) {
            throw AccountException::invalidBalanceToCreateAccount();
        }
    }

    /**
     * @throws AccountException
     */
    public function creditAccount(float $value): void
    {
        if ($value <= self::MIN_BALANCE) {
            throw AccountException::invalidCreditValue();
        }
        $this->balance += $value;
    }

    /**
     * @throws AccountException
     */
    public function debitAccount(float $value): void
    {
        if ($this->isInsufficientBalance()) {
            throw AccountException::insufficientBalance();
        }
        if (($this->balance - $value) < self::MIN_BALANCE) {
            throw AccountException::insufficientBalance();
        }
        $this->balance -= $value;
    }

    /**
     * @throws IdException
     */
    public function setId(int $value): void
    {
        $this->id = new Id($value);
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    private function isInsufficientBalance(): bool
    {
        return $this->balance <= self::MIN_BALANCE;
    }
}
