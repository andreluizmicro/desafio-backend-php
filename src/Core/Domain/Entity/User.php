<?php

declare(strict_types=1);

namespace Core\Domain\Entity;

use Core\Domain\Enum\UserType;
use Core\Domain\Exception\CnpjException;
use Core\Domain\Exception\CpfException;
use Core\Domain\Exception\EmailException;
use Core\Domain\Exception\IdException;
use Core\Domain\Exception\NameException;
use Core\Domain\Exception\PasswordException;
use Core\Domain\Exception\PersonException;
use Core\Domain\ValueObject\Cnpj;
use Core\Domain\ValueObject\Cpf;
use Core\Domain\ValueObject\Email;
use Core\Domain\ValueObject\Id;
use Core\Domain\ValueObject\Name;
use Core\Domain\ValueObject\Password;

class User
{
    /**
     * @throws PersonException
     */
    public function __construct(
        public Name $name,
        public Cpf $cpf,
        public Email $email,
        public Password $password,
        public UserType $userType,
        public ?Id $id = null,
        public ?Cnpj $cnpj = null,
    ) {
        $this->id = $this->id ?? null;
        $this->validate();
    }

    /**
     * @throws PersonException
     */
    private function validate(): void
    {
        if ($this->isDefaultUser() && ! is_null($this->cnpj)) {
            throw PersonException::invalidNaturalPerson();
        }

        if ($this->isShopkeeperUser() && is_null($this->cnpj)) {
            throw PersonException::invalidLegalPerson();
        }
    }

    /**
     * @throws EmailException
     * @throws CnpjException
     * @throws PasswordException
     * @throws IdException
     * @throws CpfException
     * @throws NameException
     * @throws PersonException
     */
    public static function createUserFactory(array $data): self
    {
        $id = $data['id'] ?? null;

        return new self(
            name: new Name($data['name']),
            cpf: new Cpf($data['cpf']),
            email: new Email($data['email']),
            password: new Password($data['password']),
            userType: UserType::tryFrom($data['user_type_id']) ?? 1,
            id: is_null($id) ? $id : new Id($id),
            cnpj: $data['cnpj'] ? new Cnpj($data['cnpj']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value ?? null,
            'name' => $this->name->value,
            'email' => $this->email->value,
            'cpf' => $this->cpf->value,
            'cnpj' => $this->cnpj->value ?? null,
            'password' => $this->password->value,
            'user_type_id' => $this->userType->value,
        ];
    }

    private function isDefaultUser(): bool
    {
        return $this->userType->value === UserType::defaultUser->value;
    }

    private function isShopkeeperUser(): bool
    {
        return $this->userType->value === UserType::shopkeeperUser->value;
    }
}
