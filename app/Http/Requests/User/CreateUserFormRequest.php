<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Core\Application\User\Create\Input;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:200'],
            'email' => ['required', 'min:3', 'max:200'],
            'cpf' => ['required'],
            'password' => ['required'],
            'cnpj' => [],
            'user_type_id' => ['required'],
        ];
    }

    public function toDto(): Input
    {
        return new Input(
            name: $this->validated('name'),
            email: $this->validated('email'),
            cpf: $this->validated('cpf'),
            password: $this->validated('password'),
            userTypeId: $this->validated('user_type_id'),
            cnpj: $this->validated('cnpj'),
        );
    }
}
