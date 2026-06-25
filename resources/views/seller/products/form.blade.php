<x-seller-layout>
    <div class="mb-6">
        <a href="{{ route('seller.productos.index') }}" class="inline-flex items-center gap-1 text-muted hover:text-text text-small transition-colors mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Volver a mis productos
        </a>
        <h1 class="text-h3 font-oxanium font-bold text-text">
            {{ isset($product) ? 'Editar Producto' : 'Nuevo Producto' }}
        </h1>
    </div>

    <div class="max-w-2xl rounded-3xl border border-border bg-background p-6 md:p-8">
        <form action="{{ isset($product) ? route('seller.productos.update', $product) : route('seller.productos.store') }}" method="POST" enctype="multipart/form-data" x-data="{ preview: null }">
            @csrf
            @if(isset($product))
                @method('PATCH')
            @endif

            <div class="space-y-5">
                <div>
                    <label for="name" class="block text-small font-medium text-text mb-1.5">Nombre del producto</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required
                        class="w-full bg-surface border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted"
                        placeholder="Ej: RTX 5090">
                    @error('name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ chars: {{ mb_strlen(old('description', $product->description ?? '')) }} }">
                    <label for="description" class="block text-small font-medium text-text mb-1.5">Descripción</label>
                    <textarea id="description" name="description" rows="4" maxlength="1000"
                        @input="chars = $event.target.value.length"
                        class="w-full bg-surface border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted"
                        placeholder="Describí el producto...">{{ old('description', $product->description ?? '') }}</textarea>
                    <div class="flex items-start justify-between gap-2 mt-1">
                        <div>
                            @error('description') <p class="text-error text-xs">{{ $message }}</p> @enderror
                        </div>
                        <span class="text-xs shrink-0 tabular-nums transition-colors"
                            :class="chars >= 1000 ? 'text-error font-semibold' : chars >= 900 ? 'text-warning' : 'text-muted'"
                            x-text="chars + ' / 1000'">
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Precio con prefijo $, formato numérico y preview USD --}}
                    <div x-data="{
                        raw: '{{ old('price', $product->price ?? '') }}',
                        display: '',
                        init() { if (this.raw) this.display = this.fmt(this.raw); },
                        fmt(n) {
                            const num = parseFloat(String(n).replace(/,/g, ''));
                            if (isNaN(num) || num === 0) return '';
                            return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        },
                        onInput(e) {
                            const cleaned = e.target.value.replace(/[^0-9.]/g, '');
                            this.display = cleaned;
                            this.raw = cleaned;
                        },
                        onBlur(e) {
                            const num = parseFloat(String(e.target.value).replace(/,/g, ''));
                            if (!isNaN(num) && num >= 0) { this.raw = num; this.display = this.fmt(num); }
                            else if (e.target.value === '') { this.raw = ''; this.display = ''; }
                        }
                    }">
                        <label for="price" class="block text-small font-medium text-text mb-1.5">Precio</label>
                        <div class="flex items-stretch rounded-xl border border-border bg-surface focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-colors overflow-hidden">
                            <span class="flex items-center px-3.5 border-r border-border bg-background/60 text-muted font-semibold text-sm select-none">$</span>
                            <input type="text" id="price" inputmode="decimal"
                                :value="display"
                                @input="onInput($event)"
                                @blur="onBlur($event)"
                                required
                                class="flex-1 bg-transparent px-4 py-2.5 text-text text-small focus:outline-none placeholder:text-muted"
                                placeholder="0.00">
                        </div>
                        <input type="hidden" name="price" :value="raw">
                        <div class="flex items-start justify-between gap-2 mt-1.5 min-h-[1rem]">
                            <p class="text-xs text-muted/70" x-show="raw > 0 && raw <= 99999999" x-cloak>
                                ≈ USD
                                <span class="font-medium text-muted"
                                    x-text="Math.round(parseFloat(raw) / {{ $usdToArs ?? 1200 }}).toLocaleString('en-US')">
                                </span>
                                <span class="text-muted/50">(blue)</span>
                            </p>
                            <p class="text-xs text-error" x-show="raw > 99999999" x-cloak>
                                Máximo permitido: $99.999.999
                            </p>
                            <span class="text-[10px] text-muted/50 shrink-0 ml-auto">mín. $1 · máx. $99.999.999</span>
                        </div>
                        @error('price') <p class="text-error text-xs mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Stock con controles +/- --}}
                    <div x-data="{ stock: {{ old('stock', $product->stock ?? 0) }} }">
                        <label for="stock" class="block text-small font-medium text-text mb-1.5">Stock disponible</label>
                        <div class="flex items-center gap-2">
                            <button type="button"
                                @click="stock = Math.max(0, stock - 1)"
                                class="flex shrink-0 items-center justify-center w-10 h-10 rounded-xl border border-border bg-surface text-muted hover:border-primary hover:text-primary transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                                </svg>
                            </button>
                            <input type="number" id="stock" name="stock" min="0"
                                x-model="stock"
                                required
                                class="w-full text-center bg-surface border border-border rounded-xl px-3 py-2.5 text-text text-small font-semibold tabular-nums focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors
                                       [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            <button type="button"
                                @click="stock++"
                                class="flex shrink-0 items-center justify-center w-10 h-10 rounded-xl border border-border bg-surface text-muted hover:border-primary hover:text-primary transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-muted/70 mt-1.5 min-h-[1rem]" x-show="stock === 0" x-cloak>
                            El producto aparecerá como <span class="text-warning font-medium">Agotado</span>
                        </p>
                        @error('stock') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div>
                    <label for="category_id" class="block text-small font-medium text-text mb-1.5">Categoría</label>
                    <select id="category_id" name="category_id" required
                        class="w-full bg-surface border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors">
                        <option value="">Seleccionar categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-small font-medium text-text mb-1.5">Imagen del producto</label>
                    @if(isset($product) && $product->image_url)
                        <div class="mb-3">
                            <p class="text-xs text-muted mb-2">Imagen actual:</p>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-32 h-32 rounded-xl object-cover border border-border">
                        </div>
                    @endif
                    <div class="flex items-center gap-4">
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-border bg-surface px-4 py-2.5 text-small text-muted hover:text-text hover:border-primary/50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                            </svg>
                            <span>Seleccionar archivo</span>
                            <input type="file" id="image" name="image" accept="image/*"
                                class="sr-only"
                                @change="preview = URL.createObjectURL($event.target.files[0])"
                                {{ !isset($product) ? 'required' : '' }}>
                        </label>
                        <span class="text-xs text-muted">PNG, JPG hasta 2MB</span>
                    </div>
                    <template x-if="preview">
                        <div class="mt-3">
                            <p class="text-xs text-muted mb-2">Nueva imagen:</p>
                            <img :src="preview" class="w-32 h-32 rounded-xl object-cover border border-border">
                        </div>
                    </template>
                    @error('image') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                @if(isset($product))
                <div class="flex items-center gap-3 rounded-xl border border-border bg-surface px-4 py-3" x-data="{ active: {{ $product->is_active ? 'true' : 'false' }} }">
                    <button type="button"
                        @click="active = !active"
                        :class="active ? 'bg-success' : 'bg-muted/30'"
                        class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none">
                        <span :class="active ? 'translate-x-4' : 'translate-x-0'"
                            class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200"></span>
                    </button>
                    <input type="hidden" name="is_active" :value="active ? '1' : '0'">
                    <div>
                        <p class="text-small font-medium text-text" x-text="active ? 'Producto activo' : 'Producto inactivo'"></p>
                        <p class="text-xs text-muted" x-text="active ? 'Visible en el catálogo' : 'Oculto del catálogo'"></p>
                    </div>
                </div>
                @endif

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-background font-semibold text-small hover:bg-primary/90 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                        </svg>
                        {{ isset($product) ? 'Guardar cambios' : 'Publicar producto' }}
                    </button>
                    <a href="{{ route('seller.productos.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-surface border border-border text-text font-medium text-small hover:bg-background transition-colors">
                        Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-seller-layout>
