<?php

declare(strict_types=1);

namespace Tests\Feature\Core\Infrastructure\Repository\Mysql;

use Core\Domain\Entity\User;
use Core\Infrastructure\Repository\Models\User as UserModel;
use Core\Infrastructure\Repository\Mysql\UserRepository;
use Tests\Feature\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testShouldReturnUser(): void
    {
        $repository = new UserRepository(new UserModel());

        $user = User::createUserFactory([
            'name' => 'André Luiz da Silva',
            'email' => 'luiz@gmail.com',
            'cpf' => '984.390.410-98',
            'password' => '1A3aaa##@$$a12',
            'user_type_id' => 1,
            'cnpj' => null,
        ]);

        $repository->create($user);

        $this->assertDatabaseHas('users', [
            'name' => 'André Luiz da Silva',
            'email' => 'luiz@gmail.com',
            'cpf' => '984.390.410-98',
            'password' => $user->getPassword(),
            'user_type_id' => 1,
            'cnpj' => null,
        ]);
    }
}
