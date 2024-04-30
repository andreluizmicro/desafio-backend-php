<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Account\Create;

use Core\Application\Account\Create\CreateAccountService;
use Core\Application\Account\Create\Input;
use Core\Domain\Entity\User;
use Core\Domain\Exception\AccountException;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\Repository\UserRepositoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateAccountServiceTest extends TestCase
{
    private AccountRepositoryInterface $accountRepositoryMock;

    private UserRepositoryInterface $userRepositoryMock;

    private User $userMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepositoryMock = Mockery::mock(AccountRepositoryInterface::class);
        $this->userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $this->userMock = Mockery::mock(User::class, [
            'name' => 'André Luiz',
            'email' => 'andre@gmail.com',
            'password' => '1@@#aass$$s2@A',
            'cpf' => '157.440.700-79',
            'usert_type_id' => 1,
            'id' => 2,
            'cnpj' => null,
        ]);
    }

    public function testShouldCreateAccount(): void
    {
        $this->accountRepositoryMock
            ->shouldReceive('existsById')
            ->once()
            ->andReturn(false);

        $this->accountRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->andReturn(2);

        $this->userRepositoryMock
            ->shouldReceive('findById')
            ->once()
            ->andReturn($this->userMock);

        $createAccountService = new CreateAccountService(
            $this->accountRepositoryMock,
            $this->userRepositoryMock,
        );

        $output = $createAccountService->execute(new Input(1));
        $this->assertEquals(2, $output->accountId);
    }

    public function testShouldReturnAccountAlreadyExists(): void
    {
        $this->expectException(AccountException::class);

        $this->accountRepositoryMock
            ->shouldReceive('existsById')
            ->once()
            ->andReturn(true);

        $createAccountService = new CreateAccountService(
            $this->accountRepositoryMock,
            $this->userRepositoryMock,
        );

        $createAccountService->execute(new Input(1));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
