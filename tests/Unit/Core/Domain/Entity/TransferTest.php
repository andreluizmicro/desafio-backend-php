<?php

declare(strict_types=1);

namespace Tests\Unit\Core\Domain\Entity;

use Core\Domain\Entity\Account;
use Core\Domain\Entity\Transfer;
use Core\Domain\Entity\User;
use Core\Domain\Exception\AccountException;
use Tests\TestCase;

class TransferTest extends TestCase
{
    private User $payerUser;

    private User $payeeUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->payerUser = $this->user = User::createUserFactory(
            $this->getUserDataProvider(1, 1)
        );

        $this->payeeUser = $this->user = User::createUserFactory(
            $this->getUserDataProvider(2, 1)
        );
    }

    public function testShouldCreateNewTransfer(): void
    {
        $payer = new Account(
            user: $this->payerUser,
            balance: 100,
        );
        $payer->setId(1);

        $payee = new Account(
            user: $this->payeeUser,
            balance: 100,
        );
        $payee->setId(2);

        $transfer = new Transfer(
            value: 100,
            payer: $payer,
            payee: $payee,
        );

        $this->assertEquals(100, $transfer->toArray()['value']);
        $this->assertEquals($payer->getId(), $transfer->getPayer()->getId());
        $this->assertEquals($payee->getId(), $transfer->getPayee()->getId());
        $this->assertEquals(100, $transfer->getValue());
    }

    public function testShouldReturnAccountExceptionWithInvalidPayer(): void
    {
        $this->expectException(AccountException::class);

        $invalidPayer = User::createUserFactory(
            $this->getUserDataProvider(2, 2, '28.019.528/0001-21')
        );
        $invalidPayer->setId(1);

        $accountWithInvalidPayer = new Account(
            user: $invalidPayer,
            balance: 100,
        );

        $payee = new Account(
            user: $this->payeeUser,
            balance: 100,
        );
        $payee->setId(2);

        new Transfer(
            value: 100,
            payer: $accountWithInvalidPayer,
            payee: $payee,
        );
    }

    private function getUserDataProvider(int $id, int $userTypeId, ?string $cnj = null): array
    {
        return [
            'id' => $id,
            'name' => 'André Luiz',
            'cpf' => '032.540.150-02',
            'email' => 'andre@gmail.com',
            'password' => '1A@##DFf@122',
            'user_type_id' => $userTypeId,
            'cnpj' => $cnj,
        ];
    }
}
