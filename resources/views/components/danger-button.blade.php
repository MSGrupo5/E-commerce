<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-2xl bg-error px-6 py-3 text-sm font-semibold text-white transition hover:bg-error/90 focus:outline-none focus:ring-2 focus:ring-error/50 focus:ring-offset-2 focus:ring-offset-background disabled:opacity-50']) }}>
    {{ $slot }}
</button>
