<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject;

use Core\Domain\Exception\CnpjException;
use Core\Domain\ValueObject\Cnpj;
use PHPUnit\Framework\TestCase;

class CnpjTest extends TestCase
{
    private const VALID_CNPJ = '04.284.931/0001-10';

    public function testShouldCreateNewCnpj(): void
    {
        $cnpj = new Cnpj(self::VALID_CNPJ);
        $this->assertEquals(self::VALID_CNPJ, $cnpj->value);
    }

    public function testShouldReturnCnpjExceptionWithInvalidValue(): void
    {
        $this->expectException(CnpjException::class);
        new Cnpj('invalid cnpj');
    }
}
