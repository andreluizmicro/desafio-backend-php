<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Account\Deposit;

use Core\Application\Account\Deposit\DepositAccountService;
use Core\Application\Account\Deposit\Input;
use Core\Domain\Entity\Account;
use Core\Domain\Entity\User;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\ValueObject\Id;
use Mockery;
use PHPUnit\Framework\TestCase;

class DepositAccountServiceTest extends TestCase
{
    public function testShouldDepositInAccount(): void
    {
        $userMock = Mockery::mock(User::class, [
            'name' => 'André Luiz',
            'email' => 'andre@gmail.com',
            'password' => '1@@#aass$$s2@A',
            'cpf' => '157.440.700-79',
            'user_type_id' => 1,
            'id' => 2,
            'cnpj' => null,
        ]);

        $idMock = Mockery::mock(Id::class, [1]);

        $accountMock = Mockery::mock(Account::class, [
            $userMock,
            100,
            $idMock,
        ]);

        $accountMock
            ->shouldReceive('creditAccount')
            ->once();

        $accountRepositoryMock = Mockery::mock(AccountRepositoryInterface::class);
        $accountRepositoryMock
            ->shouldReceive('findById')
            ->once()
            ->andReturn($accountMock);

        $accountRepositoryMock
            ->shouldReceive('updateUserBalance')
            ->once();

        $depositAccount = new DepositAccountService(
            $accountRepositoryMock
        );
        $output = $depositAccount->execute(new Input(1, 100));
        $this->assertTrue($output->success);
    }
}
