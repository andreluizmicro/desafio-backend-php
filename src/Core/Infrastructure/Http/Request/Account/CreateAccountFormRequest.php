<?php

declare(strict_types=1);

namespace Core\Infrastructure\Http\Request\Account;

use Core\Application\Account\Create\Input;
use Illuminate\Foundation\Http\FormRequest;

class CreateAccountFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required'],
        ];
    }

    public function toDto(): Input
    {
        return new Input(
            userId: $this->request->get('user_id')
        );
    }
}
