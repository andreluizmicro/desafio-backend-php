<?php

declare(strict_types=1);

namespace Core\Infrastructure\Database\Factory;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'André Luiz da Silva',
            'email' => 'andreluiz@gmail.com',
            'cpf' => '984.390.410-98',
            'password' => '1A3aaa##@$$a12',
            'user_type_id' => 1,
            'cnpj' => null,
        ];
    }
}
