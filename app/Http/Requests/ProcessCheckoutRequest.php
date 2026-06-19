<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'shipping_address'  => ['required', 'string', 'max:500'],
            'payment_method'    => ['required', Rule::in(['efectivo', 'tarjeta', 'usdt'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'shipping_address' => 'dirección de entrega',
            'payment_method'   => 'método de pago',
        ];
    }
}
