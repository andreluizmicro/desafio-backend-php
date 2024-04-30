<?php

declare(strict_types=1);

namespace Core\Domain\ValueObject;

use Core\Domain\Exception\CpfException;

final readonly class Cpf
{
    /**
     * @throws CpfException
     */
    public function __construct(public string $value)
    {
        $this->validate();
    }

    /**
     * @throws CpfException
     */
    private function validate(): void
    {
        if (! $this->isValid()) {
            throw CpfException::invalidCpf($this->value);
        }
    }

    private function isValid(): bool
    {
        $cpf = str_replace(['.', '-'], '', $this->value);

        if (preg_match('/^\d{11}$/', $cpf)) {
            return true;
        }

        return false;
    }
}
