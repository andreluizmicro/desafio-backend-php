<?php

declare(strict_types=1);

namespace Core\Infrastructure\Http\Request\User;

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
            'name' => ['required', 'string', 'min:3', 'max:200'],
            'email' => ['required', 'string', 'min:3', 'max:200'],
            'cpf' => ['required', 'string'],
            'password' => ['required', 'string'],
            'cnpj' => ['string'],
            'user_type_id' => ['required', 'integer'],
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
