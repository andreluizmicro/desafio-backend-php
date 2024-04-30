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
            'value' => ['required'],
        ];
    }

    public function toDto(): Input
    {
        return new Input(
            accountId: (int) $this->route('account_id'),
            value: $this->request->get('value'),
        );
    }
}
