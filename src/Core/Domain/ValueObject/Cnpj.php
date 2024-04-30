<?php

declare(strict_types=1);

namespace Core\Domain\ValueObject;

use Core\Domain\Exception\CnpjException;

class Cnpj
{
    /**
     * @throws CnpjException
     */
    public function __construct(public string $value)
    {
        $this->validate();
    }

    /**
     * @throws CnpjException
     */
    private function validate(): void
    {
        if (! $this->isValid()) {
            throw CnpjException::invalidCnpj($this->value);
        }
    }

    private function isValid(): bool
    {
        $cnpj = str_replace(['.', '-', '/'], '', $this->value);

        if (preg_match('/^\d{14}$/', $cnpj)) {
            return true;
        }

        return false;
    }
}
