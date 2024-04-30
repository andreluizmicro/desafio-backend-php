<?php

declare(strict_types=1);

namespace Tests\Unit\Core\Application\User\Create;

use Core\Application\User\Create\Input;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    public function testShouldCreateInputDto(): void
    {
        $input = new Input(
            name: 'André Luiz da Silva',
            email: 'andreluiz@gmail.com',
            cpf: '788.222.690-47',
            password: '123##dfd@@d2A',
            userTypeId: 1,
            cnpj: null,
        );

        $this->assertEquals($input->name, $input->toArray()['name']);
    }
}
