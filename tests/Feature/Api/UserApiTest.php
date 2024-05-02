<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Symfony\Component\HttpFoundation\Response as ResponseHttp;
use Tests\Feature\TestCase;

class UserApiTest extends TestCase
{
    protected $endpoint = '/api/v1/users';

    public function testShouldValidateRequest(): void
    {
        $response = $this->postJson($this->endpoint);
        $response->assertStatus(ResponseHttp::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testShouldCreateUser(): void
    {
        $response = $this->postJson($this->endpoint, [
            'name' => 'André Luiz da Silva',
            'email' => 'luiz@gmail.com',
            'cpf' => '984.390.410-98',
            'password' => '1A3aaa##@$$a12',
            'user_type_id' => 1,
        ]);

        $response->assertStatus(ResponseHttp::HTTP_CREATED);
    }

    public function testShouldReturnUserAlreadyExists(): void
    {
        $response = $this->postJson($this->endpoint, [
            'name' => 'André Luiz da Silva',
            'email' => 'luiz@gmail.com',
            'cpf' => '984.390',
            'password' => '1A3aaa##@$$a12',
            'user_type_id' => 1,
        ]);

        $response->assertStatus(ResponseHttp::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
        ]);
    }
}
