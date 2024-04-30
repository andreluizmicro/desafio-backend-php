<?php

declare(strict_types=1);

namespace Tests\Unit\Application\User\Create;

use Core\Application\User\Create\CreateUserService;
use Core\Application\User\Create\Input;
use Core\Domain\Repository\UserRepositoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateUserServiceTest extends TestCase
{
    public function testShouldCreateNewUser(): void
    {
        $inputMock = Mockery::mock(Input::class, [
            'name' => 'André Luiz da Silva',
            'email' => 'andreluiz@gmail.com',
            'cpf' => '984.390.410-98',
            'password' => '1A3aaa##@$$a12',
            'user_type_id' => 1,
            'cnpj' => null,
        ]);

        $inputMock
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

        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->andReturn(1);

        $createUserService = new CreateUserService($userRepositoryMock);
        $output = $createUserService->execute($inputMock);
        $this->assertEquals(1, $output->id);
    }
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
