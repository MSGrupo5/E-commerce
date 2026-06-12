<section>
    <header>
        <h2 class="text-lg font-semibold text-text">
            Información personal
        </h2>

        <p class="mt-1 text-sm text-muted">
            Actualizá tus datos personales y dirección de correo electrónico.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nombre" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="apellido" value="Apellido" />
            <x-text-input id="apellido" name="apellido" type="text" class="mt-1 block w-full" :value="old('apellido', $user->apellido)" required autocomplete="family-name" />
            <x-input-error class="mt-2" :messages="$errors->get('apellido')" />
        </div>

        <div>
            <x-input-label for="direccion_entrega" value="Dirección de entrega" />
            <x-text-input id="direccion_entrega" name="direccion_entrega" type="text" class="mt-1 block w-full" :value="old('direccion_entrega', $user->direccion_entrega)" autocomplete="street-address" />
            <x-input-error class="mt-2" :messages="$errors->get('direccion_entrega')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-2xl border border-warning/20 bg-warning/5 p-4">
                    <p class="text-sm text-warning">
                        Tu dirección de email no está verificada.

                        <button form="send-verification" class="ml-1 font-semibold underline hover:text-warning/80 transition-colors">
                            Reenviar verificación
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-success">
                            Se envió un nuevo enlace de verificación a tu email.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Guardar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-success"
                >Guardado.</p>
            @endif
        </div>
    </form>
</section>
