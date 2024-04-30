<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\User;
use Core\Domain\Exception\PersonException;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testShouldCreateUser(): void
    {
        $data = $this->getUserDataProvider();
        $user = User::createUserFactory($data);

        $this->assertNull($user->id);
        $this->assertEquals($data['name'], $user->name->value);
        $this->assertEquals($data['cpf'], $user->cpf->value);
        $this->assertEquals($data['email'], $user->email->value);
        $this->assertNotNull($data['password'], $user->password->value);
        $this->assertEquals($data['user_type_id'], $user->userType->value);
        $this->assertNotEquals($data['password'], $user->toArray()['password']);
    }

    public function testShouldReturnPersonExceptionWithInvalidNaturalPerson(): void
    {
        $this->expectException(PersonException::class);

        $data = $this->getUserDataProvider('28.019.528/0001-21');
        User::createUserFactory($data);
    }

    public function testShouldReturnPersonExceptionWithInvalidLegalPerson(): void
    {
        $this->expectException(PersonException::class);

        $data = $this->getUserDataProvider(userTypeId: 2);
        User::createUserFactory($data);
    }

    private function getUserDataProvider(?string $cnpj = null, ?int $userTypeId = null): array
    {
        return [
            'name' => 'André Luiz',
            'cpf' => '032.540.150-02',
            'email' => 'andre@gmail.com',
            'password' => '1A@##DFf@122',
            'user_type_id' => $userTypeId ?? 1,
            'cnpj' => $cnpj ?? null,
        ];
    }
}

