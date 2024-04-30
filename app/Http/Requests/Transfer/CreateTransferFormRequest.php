<?php

declare(strict_types=1);

namespace App\Http\Requests\Transfer;

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
            'payer' => ['required'],
            'payee' => ['required'],
        ];
    }

    public function toDto(): Input
    {
        return new Input(
            value: $this->request->get('value'),
            payerId: $this->request->get('payer'),
            payeeId: $this->request->get('payee')
        );
    }
}
