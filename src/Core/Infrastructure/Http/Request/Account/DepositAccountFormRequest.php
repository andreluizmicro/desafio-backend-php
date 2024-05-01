<?php

declare(strict_types=1);

namespace Core\Infrastructure\Http\Request\Account;

use Core\Application\Account\Deposit\Input;
use Illuminate\Foundation\Http\FormRequest;

class DepositAccountFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric'],
        ];
    }

    public function toDto(): Input
    {
        $this->validated();

        return new Input(
            accountId: (int) $this->route('account_id'),
            value: (float) $this->validated('value'),
        );
    }
}
