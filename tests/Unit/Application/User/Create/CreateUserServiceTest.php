<?php

declare(strict_types=1);

namespace Tests\Unit\Application\User\Create;

use Core\Application\User\Create\CreateUserService;
use Core\Application\User\Create\Input;
use Core\Domain\Exception\UserException;
use Core\Domain\Repository\UserRepositoryInterface;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateUserServiceTest extends TestCase
{
    private Input $inputMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inputMock = Mockery::mock(Input::class, [
            'name' => 'André Luiz da Silva',
            'email' => 'andreluiz@gmail.com',
            'cpf' => '984.390.410-98',
            'password' => '1A3aaa##@$$a12',
            'user_type_id' => 1,
            'cnpj' => null,
        ]);

        $this->inputMock
            ->shouldReceive('toArray')
            ->once()
            ->andReturn([
                'name' => 'André Luiz da Silva',
                'email' => 'andreluiz@gmail.com',
                'cpf' => '984.390.410-98',
                'password' => '1A3aaa##@$$a12',
                'user_type_id' => 1,
                'cnpj' => null,
            ]);
    }

    public function testShouldCreateNewUser(): void
    {
        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->andReturn(1);

        $createUserService = new CreateUserService($userRepositoryMock);
        $output = $createUserService->execute($this->inputMock);
        $this->assertEquals(1, $output->id);
    }

    public function testShouldReturnUserExceptionWhenUserIsInvalid(): void
    {
        $this->expectException(UserException::class);

        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->andThrow(new UserException());

        $createUserService = new CreateUserService($userRepositoryMock);
        $createUserService->execute($this->inputMock);
    }

    public function testShouldReturnUserExceptionWithInvalidArgument(): void
    {
        $this->expectException(Exception::class);

        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->andThrow(new Exception());

        $createUserService = new CreateUserService($userRepositoryMock);
        $createUserService->execute($this->inputMock);
    }
}
