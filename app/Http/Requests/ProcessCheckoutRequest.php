<?php

namespace App\Http\Requests;

use App\Models\Cart;
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
            'payment_method'    => [
                'required',
                Rule::in(['efectivo', 'tarjeta', 'usdt']),
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value === 'efectivo' && ! $this->efectivoDisponible()) {
                        $fail('El pago en efectivo solo está disponible si vos y todos los vendedores del pedido son de la misma provincia y ciudad.');
                    }
                },
            ],
            'notes'             => ['nullable', 'string', 'max:500'],
        ];
    }

    private function efectivoDisponible(): bool
    {
        $cart = Cart::with('items.product.seller')->where('user_id', auth()->id())->first();

        return $cart && $cart->efectivoDisponiblePara(auth()->user());
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
