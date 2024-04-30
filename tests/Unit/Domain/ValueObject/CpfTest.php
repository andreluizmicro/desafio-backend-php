<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject;

use Core\Domain\Exception\CpfException;
use Core\Domain\ValueObject\Cpf;
use PHPUnit\Framework\TestCase;

class CpfTest extends TestCase
{
    private const VALID_CPF = '559.854.560-80';

    public function testShouldCreateNewCpf(): void
    {
        $cpf= new Cpf(self::VALID_CPF);
        $this->assertEquals(self::VALID_CPF, $cpf->value);
    }

    public function testShouldReturnCpfExceptionWithInvalidValue(): void
    {
        $this->expectException(CpfException::class);
        new Cpf('invalid cpf');
    }
}
