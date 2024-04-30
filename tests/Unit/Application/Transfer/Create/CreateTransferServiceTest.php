<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Transfer\Create;

use Core\Application\Transfer\Create\CreateTransferService;
use Core\Application\Transfer\Create\Input;
use Core\Domain\Adapter\UnitOfWorkAdapterInterface;
use Core\Domain\Entity\Account;
use Core\Domain\Entity\User;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Gateway\AuthorizationGatewayInterface;
use Core\Domain\Gateway\NotificationGatewayInterface;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\Repository\TransferRepositoryInterface;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateTransferServiceTest extends TestCase
{
    private AccountRepositoryInterface $accountRepositoryMock;
    private TransferRepositoryInterface $transferRepositoryMock;
    private AuthorizationGatewayInterface $authorizationGatewayMock;
    private NotificationGatewayInterface $notificationGatewayMock;
    private UnitOfWorkAdapterInterface $unitOfWorkAdapterMock;
    private User $payerMock;
    private User $payeeMock;
    private Account $accountMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepositoryMock = Mockery::mock(AccountRepositoryInterface::class);
        $this->transferRepositoryMock = Mockery::mock(TransferRepositoryInterface::class);
        $this->authorizationGatewayMock = Mockery::mock(AuthorizationGatewayInterface::class);
        $this->notificationGatewayMock = Mockery::mock(NotificationGatewayInterface::class);
        $this->unitOfWorkAdapterMock = Mockery::mock(UnitOfWorkAdapterInterface::class);
    }

    public function testShouldRollbackTransaction(): void
    {
        $this->expectException(Exception::class);

        $this->accountRepositoryMock
            ->shouldReceive('findByUserId')
            ->once()
            ->andThrow(new NotFoundException());

        $this->unitOfWorkAdapterMock
            ->shouldReceive('rollback')
            ->once();

        $createTransferService = new CreateTransferService(
            $this->accountRepositoryMock,
            $this->transferRepositoryMock,
            $this->authorizationGatewayMock,
            $this->notificationGatewayMock,
            $this->unitOfWorkAdapterMock,
        );

        $createTransferService->execute(
            new Input(
                value: 100,
                payerId: 1,
                payeeId: 2
            )
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
