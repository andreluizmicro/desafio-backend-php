<?php

declare(strict_types=1);

namespace Tests\Unit\Core\Domain\Entity;

use Core\Domain\Entity\User;
use Core\Domain\Enum\UserType;
use Core\Domain\Exception\PersonException;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testShouldCreateUser(): void
    {
        $data = $this->getUserDataProvider();
        $user = User::createUserFactory($data);

        $this->assertNull($user->getId());
        $this->assertEquals($data['name'], $user->getName());
        $this->assertEquals($data['cpf'], $user->getCpf());
        $this->assertEquals($data['email'], $user->getEmail());
        $this->assertNotNull($data['password'], $user->getPassword());
        $this->assertEquals($data['user_type_id'], $user->getUserType());
        $this->assertNotEquals($data['password'], $user->toArray()['password']);
        $this->assertEquals($user->getUserType(), UserType::fromInt($data['user_type_id'])->value);
        $this->assertNull($user->getCnpj());
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
