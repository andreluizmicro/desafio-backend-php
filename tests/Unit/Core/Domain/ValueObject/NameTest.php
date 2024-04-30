<?php

declare(strict_types=1);

namespace Tests\Unit\Core\Domain\ValueObject;

use Core\Domain\Exception\NameException;
use Core\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    private const VALID_NAME = 'André Luiz da Silva';

    public function testShouldCreateNewName(): void
    {
        $name = new Name(self::VALID_NAME);
        $this->assertEquals(self::VALID_NAME, $name->value);
    }

    public function testShouldReturnNameExceptionWithInvalidValue(): void
    {
        $this->expectException(NameException::class);
        new Name('AA');
    }
}
