<?php

declare(strict_types=1);

namespace Core\Application\User\Create;

class Input
{
    public function __construct(
        public string $name,
        public string $email,
        public string $cpf,
        public string $password,
        public int $userTypeId,
        public ?string $cnpj = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'password' => $this->password,
            'user_type_id' => $this->userTypeId,
            'cnpj' => $this->cnpj,
        ];
    }
}
