@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-h3 font-oxanium font-bold text-text">Categorías</h1>
            <p class="text-muted text-body">{{ $categories->count() }} {{ $categories->count() === 1 ? 'categoría' : 'categorías' }}</p>
        </div>

        <button type="button" @click="$dispatch('open-create-modal')"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-background font-semibold text-small hover:bg-primary/90 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nueva Categoría
        </button>
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

    <div x-data="{
        modal: false,
        editing: false,
        form: { name: '' },
        editId: null,

        openCreate() {
            this.editing = false;
            this.form = { name: '' };
            this.modal = true;
        },

        openEdit(category) {
            this.editing = true;
            this.editId = category.id;
            this.form = { name: category.name };
            this.modal = true;
        },

        submit() {
            if (this.editing) {
                $refs.editForm.submit();
            } else {
                $refs.createForm.submit();
            }
        }
    }" @open-create-modal.window="openCreate()">

        <div class="rounded-3xl border border-border bg-surface overflow-hidden">
            <table class="w-full text-left text-small">
                <thead class="sticky top-0 z-10 bg-surface">
                    <tr class="border-b border-border text-muted text-[11px] uppercase tracking-[0.15em]">
                        <th class="px-5 py-4 font-semibold">Nombre</th>
                        <th class="px-5 py-4 font-semibold">Slug</th>
                        <th class="px-5 py-4 font-semibold">Productos</th>
                        <th class="px-5 py-4 font-semibold text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($categories as $category)
                        <tr class="hover:bg-background/50 transition-colors">
                            <td class="px-5 py-4">
                                <span class="font-medium text-text">{{ $category->name }}</span>
                            </td>
                            <td class="px-5 py-4 text-muted text-[12px]">{{ $category->slug }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 text-muted">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                    </svg>
                                    {{ $category->products_count }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="openEdit({{ $category->toJson() }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-[12px] font-medium hover:bg-primary/20 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                        </svg>
                                        Editar
                                    </button>
                                    <form action="{{ route('admin.categorias.destroy', $category) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar «{{ $category->name }}»?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-error hover:bg-error/10 text-[12px] font-medium transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-muted">No hay categorías.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal Create/Edit --}}
        <div x-show="modal"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            @keydown.escape.window="modal = false">

            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="modal = false"></div>

            <div x-show="modal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-md rounded-3xl border border-border bg-surface p-6 shadow-[0_32px_80px_rgba(0,0,0,0.5)]">

                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-semibold text-text" x-text="editing ? 'Editar categoría' : 'Nueva categoría'"></h3>
                    <button type="button" @click="modal = false" class="text-muted hover:text-text transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 18 12-12m0 12L6 6" />
                        </svg>
                    </button>
                </div>

                {{-- Create form --}}
                <form x-ref="createForm" method="POST" action="{{ route('admin.categorias.store') }}" x-show="!editing">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-small font-medium text-text mb-1.5">Nombre</label>
                            <input type="text" name="name" x-model="form.name" required maxlength="255"
                                class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-small text-text outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-colors"
                                placeholder="Ej: Teclados mecánicos">
                        </div>
                        <div class="flex gap-3 pt-1">
                            <button type="button" @click="modal = false"
                                class="flex-1 rounded-2xl border border-border bg-background px-4 py-3 text-sm font-medium text-text hover:bg-surface transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="flex-1 rounded-2xl bg-primary px-4 py-3 text-sm font-semibold text-background hover:bg-primary/90 transition-colors">
                                Crear
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Edit form --}}
                <form x-ref="editForm" method="POST" :action="`/admin/categorias/${editId}`" x-show="editing">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-small font-medium text-text mb-1.5">Nombre</label>
                            <input type="text" name="name" x-model="form.name" required maxlength="255"
                                class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-small text-text outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-colors"
                                placeholder="Ej: Teclados mecánicos">
                        </div>
                        <div class="flex gap-3 pt-1">
                            <button type="button" @click="modal = false"
                                class="flex-1 rounded-2xl border border-border bg-background px-4 py-3 text-sm font-medium text-text hover:bg-surface transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="flex-1 rounded-2xl bg-primary px-4 py-3 text-sm font-semibold text-background hover:bg-primary/90 transition-colors">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
