<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-2xl border border-border bg-surface px-6 py-3 text-sm font-semibold text-text transition hover:bg-background focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2 focus:ring-offset-background disabled:opacity-50']) }}>
    {{ $slot }}
</button>
