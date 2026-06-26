<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validamos incluyendo el campo apellido (MSGRUP-27)
        $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{L}\s\-]+$/u'],
            'apellido' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{L}\s\-]+$/u'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.regex'     => 'El nombre solo puede contener letras, espacios o guiones.',
            'name.min'       => 'El nombre debe tener al menos 2 caracteres.',
            'name.max'       => 'El nombre no puede superar los 50 caracteres.',
            'apellido.regex' => 'El apellido solo puede contener letras, espacios o guiones.',
            'apellido.min'   => 'El apellido debe tener al menos 2 caracteres.',
            'apellido.max'   => 'El apellido no puede superar los 50 caracteres.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'usuario',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('products.index'));
    }
}
