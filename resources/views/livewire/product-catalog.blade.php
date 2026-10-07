<div>
    <div class="mb-8">
        <input
            type="text"
            wire:model.live.debounce.400ms="search"
            placeholder="Cari produk..."
            class="input max-w-md mb-4"
        >

        <div class="flex flex-wrap gap-2">
            <button wire:click="$set('categoryId', null)" class="pill {{ !$categoryId ? 'pill-active' : '' }}">
                Semua
            </button>
            @foreach ($categories as $category)
                <button wire:click="$set('categoryId', {{ $category->id }})" class="pill {{ (string) $categoryId === (string) $category->id ? 'pill-active' : '' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <div wire:loading.delay class="text-sm text-muted mb-4">Memuat...</div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-10">
        @forelse ($products as $product)
            @php $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
            <a href="{{ route('products.show', $product->slug) }}" wire:key="product-{{ $product->id }}" class="group block">
                <div class="aspect-[4/3] bg-line/30 rounded-sm overflow-hidden mb-3">
                    @if ($primary)
                        <img src="{{ Storage::url($primary->path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @endif
                </div>
                <span class="badge bg-primary/5 text-primary">{{ $product->category->name }}</span>
                <h3 class="text-sm leading-snug mt-2 mb-1">{{ $product->name }}</h3>
                <p class="font-display font-bold text-lg">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
            </a>
        @empty
            <p class="col-span-full text-center text-muted py-16">Produk tidak ditemukan. Coba kata kunci lain.</p>
        @endforelse
    </div>

    <div class="mt-10">{{ $products->links() }}</div>
</div>