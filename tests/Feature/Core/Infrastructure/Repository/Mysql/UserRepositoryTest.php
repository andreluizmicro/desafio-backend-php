<?php

declare(strict_types=1);

namespace Tests\Feature\Core\Infrastructure\Repository\Mysql;

use Core\Domain\Entity\User;
use Core\Domain\Exception\UserException;
use Core\Domain\ValueObject\Id;
use Core\Infrastructure\Repository\Models\User as UserModel;
use Core\Infrastructure\Repository\Mysql\UserRepository;
use Tests\Feature\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testShouldReturnUser(): void
    {
        $repository = new UserRepository(new UserModel());

        $user = $this->userDataProvider();

        $repository->create($user);
        $this->assertDatabaseHas('users', [
            'name' => 'André Luiz da Silva',
        ]);
    }

    public function testShouldReturnIdWhenUserAlreadyExists(): void
    {
        $repository = new UserRepository(new UserModel());
        $user = $this->userDataProvider();

        $userCreated = $repository->create($user);
        $userFound = $repository->create($user);

        $this->assertEquals($userCreated, $userFound);
    }

    public function testShouldReturnUserById(): void
    {
        $repository = new UserRepository(new UserModel());
        $user = $this->userDataProvider();

        $userId = $repository->create($user);
        $userFound = $repository->findById(new Id($userId));
        $this->assertEquals($userId, $userFound->getId());
        $this->assertDatabaseHas('users', [
            'name' => $userFound->getName(),
        ]);
    }

    public function testShouldReturnNotFoundUser(): void
    {
        $this->expectException(UserException::class);
        $repository = new UserRepository(new UserModel());
        $repository->findById(new Id(1));
    }

    private function userDataProvider(): User
    {
        return User::createUserFactory([
            'name' => 'André Luiz da Silva',
            'email' => 'luiz@gmail.com',
            'cpf' => '984.390.410-98',
            'password' => '1A3aaa##@$$a12',
            'user_type_id' => 1,
            'cnpj' => null,
        ]);
    }
}
