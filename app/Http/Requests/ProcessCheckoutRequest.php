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
            'phone'             => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{6,30}$/'],
            'payment_method'    => ['required', Rule::in(['efectivo', 'tarjeta', 'usdt'])],
            'notes'             => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'shipping_address' => 'dirección de entrega',
            'phone'            => 'teléfono de contacto',
            'payment_method'   => 'método de pago',
            'notes'            => 'notas adicionales',
        ];
    }
}
