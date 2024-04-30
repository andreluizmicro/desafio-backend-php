<?php

declare(strict_types=1);

namespace Tests\Unit\Core\Domain\ValueObject;

use Core\Domain\Exception\PasswordException;
use Core\Domain\ValueObject\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    private const VALID_PASSWORD = '1@#$%&@@!11aa!aaAC';

    public function testShouldCreateNewPassword(): void
    {
        $password = new Password(self::VALID_PASSWORD);
        $this->assertNotEquals(self::VALID_PASSWORD, $password->__toString());
    }

    public function testShouldReturnPasswordExceptionWithInvalidValue(): void
    {
        $this->expectException(PasswordException::class);
        new Password('123456');
    }
}
