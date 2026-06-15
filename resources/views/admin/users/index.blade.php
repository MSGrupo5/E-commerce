@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-h3 font-oxanium font-bold text-text">Usuarios</h1>
            <p class="text-muted text-body">Total: {{ $users->total() }}</p>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-3">
            <select name="status"
                onchange="this.form.submit()"
                class="rounded-xl border border-border bg-surface px-4 py-2.5 text-small text-text outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-colors">
                <option value="">Todos</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
            </select>

            @if(request('status'))
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-muted hover:text-text hover:bg-surface border border-border text-small transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-2xl border border-success/30 bg-success/10 px-5 py-3 text-small text-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-2xl border border-error/30 bg-error/10 px-5 py-3 text-small text-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-3xl border border-border bg-surface overflow-hidden max-h-[600px]">
        <div class="overflow-x-auto overflow-y-auto">
            <table class="w-full text-left text-small">
                <thead class="sticky top-0 z-10 bg-surface">
                    <tr class="border-b border-border text-muted text-[11px] uppercase tracking-[0.15em]">
                        <th class="px-5 py-4 font-semibold">Nombre</th>
                        <th class="px-5 py-4 font-semibold">Email</th>
                        <th class="px-5 py-4 font-semibold">Rol</th>
                        <th class="px-5 py-4 font-semibold">Estado</th>
                        <th class="px-5 py-4 font-semibold">Registro</th>
                        <th class="px-5 py-4 font-semibold text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($users as $user)
                        <tr class="hover:bg-background/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary text-sm font-bold shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-text">{{ $user->name }} {{ $user->apellido }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-muted">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wider {{ $user->isAdmin() ? 'bg-primary/10 text-primary border border-primary/20' : 'bg-background text-muted border border-border' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($user->is_active)
                                    <span class="inline-flex items-center gap-1.5 text-success">
                                        <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-error">
                                        <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-muted text-[12px]">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                @if(!$user->isAdmin())
                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-small font-medium transition-colors {{ $user->is_active ? 'text-warning hover:bg-warning/10' : 'text-success hover:bg-success/10' }}">
                                            @if($user->is_active)
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                                </svg>
                                                Desactivar
                                            @else
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                Activar
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted text-[12px]">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-muted">No hay usuarios.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->withQueryString()->links() }}
    </div>
@endsection
