{{-- resources/views/components/ui/category-filter.blade.php --}}
{{-- Props: $categories (Collection|null), $categorySlug (string|null), $frontOnly (bool) --}}
@props(['categories' => null, 'categorySlug' => null, 'frontOnly' => true])

@php
    // If no categories were provided (backend not implemented yet), use a small sample set for the frontend demo
    if (empty($categories) || !count($categories)) {
        $categories = collect([
            (object)['name' => 'Laptops', 'slug' => 'laptops', 'products_count' => 24],
            (object)['name' => 'Smartphones', 'slug' => 'smartphones', 'products_count' => 18],
            (object)['name' => 'Auriculares', 'slug' => 'auriculares', 'products_count' => 32],
            (object)['name' => 'Monitores', 'slug' => 'monitores', 'products_count' => 15],
        ]);
    }

    $totalProducts = $categories->sum('products_count');
@endphp

<div class="rounded-3xl border border-border bg-surface p-5 sm:p-6">
    <div class="mb-6">
        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-muted">Categorías</h3>
    </div>

    <div class="space-y-2">
        @if($frontOnly)
            <button type="button" data-slug="" class="w-full flex items-center justify-between rounded-2xl border px-4 py-3 text-sm font-medium transition-colors {{ empty($categorySlug) ? 'border-primary bg-primary/10 text-primary' : 'border-border bg-surface text-text' }}">
                <span>Todos</span>
                <span class="text-muted">{{ $totalProducts }}</span>
            </button>

            @foreach($categories as $category)
                <button type="button" data-slug="{{ $category->slug }}" class="w-full flex items-center justify-between rounded-2xl border px-4 py-3 text-sm font-medium transition-colors {{ $categorySlug === $category->slug ? 'border-primary bg-primary/10 text-primary' : 'border-border bg-surface text-text' }}">
                    <span>{{ $category->name }}</span>
                    <span class="text-muted">{{ $category->products_count }}</span>
                </button>
            @endforeach
        @else
            <a href="{{ route('products.index') }}" class="flex items-center justify-between rounded-2xl border px-4 py-3 text-sm font-medium transition-colors {{ empty($categorySlug) ? 'border-primary bg-primary/10 text-primary' : 'border-border bg-surface text-text' }}">
                <span>Todos</span>
                <span class="text-muted">{{ $totalProducts }}</span>
            </a>

            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="flex items-center justify-between rounded-2xl border px-4 py-3 text-sm font-medium transition-colors {{ $categorySlug === $category->slug ? 'border-primary bg-primary/10 text-primary' : 'border-border bg-surface text-text' }}">
                    <span>{{ $category->name }}</span>
                    <span class="text-muted">{{ $category->products_count }}</span>
                </a>
            @endforeach
        @endif
    </div>
</div>
