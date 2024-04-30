<?php

declare(strict_types=1);

namespace Core\Infrastructure\Http\Request\Transfer;

use Core\Application\Transfer\Create\Input;
use Illuminate\Foundation\Http\FormRequest;

class CreateTransferFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['required'],
            'payer' => 'required|different:payee',
            'payee' => 'required|numeric',
        ];
    }

    public function toDto(): Input
    {
        return new Input(
            value: $this->validated('value'),
            payerId: $this->validated('payer'),
            payeeId: $this->validated('payee')
        );
    }
}
