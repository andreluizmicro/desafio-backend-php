<?php

declare(strict_types=1);

namespace Tests\Unit\Core\Application\Transfer\Create;

use Core\Application\Transfer\Create\CreateTransferService;
use Core\Application\Transfer\Create\Input;
use Core\Domain\Adapter\UnitOfWorkAdapterInterface;
use Core\Domain\Entity\Account;
use Core\Domain\Entity\User;
use Core\Domain\Exception\AccountException;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Gateway\AuthorizationGatewayInterface;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\Repository\TransferRepositoryInterface;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Shared\Domain\Adapter\EventAdapterInterface;

class CreateTransferServiceTest extends TestCase
{
    private AccountRepositoryInterface $accountRepositoryMock;

    private TransferRepositoryInterface $transferRepositoryMock;

    private AuthorizationGatewayInterface $authorizationGatewayMock;

    private UnitOfWorkAdapterInterface $unitOfWorkAdapterMock;

    private EventAdapterInterface $eventAdapterMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepositoryMock = Mockery::mock(AccountRepositoryInterface::class);
        $this->transferRepositoryMock = Mockery::mock(TransferRepositoryInterface::class);
        $this->authorizationGatewayMock = Mockery::mock(AuthorizationGatewayInterface::class);
        $this->unitOfWorkAdapterMock = Mockery::mock(UnitOfWorkAdapterInterface::class);
        $this->eventAdapterMock = Mockery::mock(EventAdapterInterface::class);
    }

    public function testShouldCreateTransfer(): void
    {
        $user = User::createUserFactory([
            'name' => 'André Luiz',
            'email' => 'andre@gmail.com',
            'password' => '1@@#aass$$s2@A',
            'cpf' => '157.440.700-79',
            'user_type_id' => 1,
            'id' => 2,
            'cnpj' => null,
        ]);

        $accountMock = Mockery::mock(Account::class, [
            $user,
            500,
        ]);

        $accountMock
            ->shouldReceive('creditAccount')
            ->once();

        $accountMock
            ->shouldReceive('debitAccount')
            ->once();

        $this->accountRepositoryMock
            ->shouldReceive('findById')
            ->times(2)
            ->andReturn($accountMock);

        $this->authorizationGatewayMock
            ->shouldReceive('authorizeTransfer')
            ->once();

        $this->unitOfWorkAdapterMock
            ->shouldReceive('begin')
            ->once();

        $this->transferRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->andReturn(1);

        $this->accountRepositoryMock
            ->shouldReceive('updateUserBalance')
            ->times(2);

        $this->unitOfWorkAdapterMock
            ->shouldReceive('commit')
            ->once();

        $this->eventAdapterMock
            ->shouldReceive('publish')
            ->once();

        $createTransferService = new CreateTransferService(
            $this->accountRepositoryMock,
            $this->transferRepositoryMock,
            $this->authorizationGatewayMock,
            $this->unitOfWorkAdapterMock,
            $this->eventAdapterMock
        );

        $output = $createTransferService->execute(
            new Input(
                value: 100,
                payerId: 1,
                payeeId: 2
            )
        );

        $this->assertEquals(1, $output->transferId);
    }

    public function testShouldRollbackTransaction(): void
    {
        $this->expectException(Exception::class);

        $this->accountRepositoryMock
            ->shouldReceive('findById')
            ->andThrow(new NotFoundException());

        $this->unitOfWorkAdapterMock
            ->shouldReceive('rollback')
            ->once();

        $createTransferService = new CreateTransferService(
            $this->accountRepositoryMock,
            $this->transferRepositoryMock,
            $this->authorizationGatewayMock,
            $this->unitOfWorkAdapterMock,
            $this->eventAdapterMock
        );

        $createTransferService->execute(
            new Input(
                value: 100,
                payerId: 1,
                payeeId: 2
            )
        );
    }

    public function testShouldReturnAccountExceptionWithInvalidTransaction(): void
    {
        $this->expectException(AccountException::class);

        $createTransferService = new CreateTransferService(
            $this->accountRepositoryMock,
            $this->transferRepositoryMock,
            $this->authorizationGatewayMock,
            $this->unitOfWorkAdapterMock,
            $this->eventAdapterMock
        );

        $createTransferService->execute(
            new Input(
                value: 100,
                payerId: 1,
                payeeId: 1
            )
        );
    }
}
