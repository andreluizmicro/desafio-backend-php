<?php

declare(strict_types=1);

namespace Tests\Unit\Core\Domain\Entity;

use Core\Domain\Entity\Account;
use Core\Domain\Entity\User;
use Core\Domain\Exception\AccountException;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::createUserFactory(
            $this->getUserDataProvider()
        );
    }

    public function testShouldCreateNewAccount(): void
    {
        $balance = 100;
        $account = new Account(
            user: $this->user,
            balance: $balance,
        );

        $this->assertEquals($balance, $account->getBalance());
    }

    public function testShouldReturnAccountExceptionWithInvalidBalance(): void
    {
        $this->expectException(AccountException::class);
        new Account(
            user: $this->user,
            balance: -100
        );
    }

    public function testShouldInsertCreditInAccount(): void
    {
        $balance = 0;
        $account = new Account(
            user: $this->user,
            balance: $balance,
        );
        $credit = 1500;

        $account->creditAccount($credit);
        $this->assertEquals($credit, $account->getBalance());
    }

    public function testShouldReturnAccountExceptionWithInvalidValueToCreditAccount(): void
    {
        $this->expectException(AccountException::class);
        $account = new Account(
            user: $this->user,
            balance: 0,
        );
        $account->creditAccount(-150);
    }

    public function testShouldDebitInAccount(): void
    {
        $account = new Account(
            user: $this->user,
            balance: 1500,
        );
        $account->debitAccount(500);
        $this->assertEquals(1000, $account->getBalance());
    }

    public function testShouldReturnAccountExceptionWithInsufficientBalance(): void
    {
        $this->expectException(AccountException::class);
        $account = new Account(
            user: $this->user,
            balance: 0,
        );
        $account->debitAccount(100);
    }

    public function testShouldReturnAccountExceptionWhenTryDebit(): void
    {
        $this->expectException(AccountException::class);
        $account = new Account(
            user: $this->user,
            balance: 100,
        );
        $account->debitAccount(200);
    }

    private function getUserDataProvider(): array
    {
        return [
            'name' => 'André Luiz',
            'cpf' => '032.540.150-02',
            'email' => 'andre@gmail.com',
            'password' => '1A@##DFf@122',
            'user_type_id' => 1,
            'cnpj' => null,
        ];
    }
}
