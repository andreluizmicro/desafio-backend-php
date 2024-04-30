<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject;

use Core\Domain\Exception\IdException;
use Core\Domain\ValueObject\Id;
use PHPUnit\Framework\TestCase;

class IdTest extends TestCase
{
    private const VALID_ID = 1;

    public function testShouldCreateNewId(): void
    {
        $id= new Id(self::VALID_ID);
        $this->assertEquals(self::VALID_ID, $id->value);
    }

    public function testShouldReturnIdExceptionWithInvalidValue(): void
    {
        $this->expectException(IdException::class);
        new Id(0);
    }
}
