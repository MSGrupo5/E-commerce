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
            <x-input-label for="info_entrega" value="Dirección de entrega" />
            <x-text-input id="info_entrega" name="info_entrega" type="text" class="mt-1 block w-full" :value="old('info_entrega', $user->info_entrega)" autocomplete="street-address" />
            <x-input-error class="mt-2" :messages="$errors->get('info_entrega')" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="provincia" value="Provincia" />
                <select id="provincia" name="provincia"
                    class="mt-1 block w-full rounded-md border-border bg-background text-text shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Sin especificar</option>
                    @foreach (\App\Models\User::PROVINCIAS as $provincia)
                        <option value="{{ $provincia }}" @selected(old('provincia', $user->provincia) === $provincia)>{{ $provincia }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('provincia')" />
            </div>

            <div>
                <x-input-label for="ciudad" value="Ciudad" />
                <x-text-input id="ciudad" name="ciudad" type="text" class="mt-1 block w-full" :value="old('ciudad', $user->ciudad)" autocomplete="address-level2" />
                <x-input-error class="mt-2" :messages="$errors->get('ciudad')" />
            </div>
        </div>

        <p class="text-xs text-muted">
            Completá tu provincia y ciudad para poder coordinar un punto de encuentro y habilitar el pago en efectivo con vendedores de tu misma zona.
        </p>

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
