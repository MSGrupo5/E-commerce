<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{L}\s\-]+$/u'],
            'email'    => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'apellido' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{L}\s\-]+$/u'],
            'info_entrega' => ['nullable', 'string', 'max:500'],
            'provincia' => ['nullable', 'string', Rule::in(User::PROVINCIAS)],
            'ciudad' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex'     => 'El nombre solo puede contener letras, espacios o guiones.',
            'name.min'       => 'El nombre debe tener al menos 2 caracteres.',
            'name.max'       => 'El nombre no puede superar los 50 caracteres.',
            'apellido.regex' => 'El apellido solo puede contener letras, espacios o guiones.',
            'apellido.min'   => 'El apellido debe tener al menos 2 caracteres.',
            'apellido.max'   => 'El apellido no puede superar los 50 caracteres.',
        ];
    }
}
