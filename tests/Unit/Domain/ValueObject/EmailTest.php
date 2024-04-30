<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject;

use Core\Domain\Exception\EmailException;
use Core\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    private const VALID_EMAIL = 'andre@gmail.com';

    public function testShouldCreateNewEmail(): void
    {
        $email = new Email(self::VALID_EMAIL);
        $this->assertEquals(self::VALID_EMAIL, $email->value);
    }

    public function testShouldReturnEmailExceptionWithInvalidValue(): void
    {
        $this->expectException(EmailException::class);
        new Email('invalid email');
    }
}
