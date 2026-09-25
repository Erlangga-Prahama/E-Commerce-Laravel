<div>
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <input
            type="text"
            wire:model.live.debounce.400ms="search"
            placeholder="Cari produk..."
            class="flex-1 border rounded p-2"
        >

        <select wire:model.live="categoryId" class="border rounded p-2">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        @if ($search || $categoryId)
            <button wire:click="clearFilters" class="text-sm text-gray-500 underline">
                Reset Filter
            </button>
        @endif
    </div>

    <div wire:loading.delay class="text-sm text-gray-500 mb-4">Memuat...</div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse ($products as $product)
            @php $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
            <a href="{{ route('products.show', $product->slug) }}" wire:key="product-{{ $product->id }}" class="block bg-white rounded shadow overflow-hidden hover:shadow-md">
                @if ($primary)
                    <img src="{{ Storage::url($primary->path) }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gray-200"></div>
                @endif
                <div class="p-3">
                    <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                    <h3 class="font-medium text-sm truncate">{{ $product->name }}</h3>
                    <p class="text-blue-600 font-bold mt-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </a>
        @empty
            <p class="col-span-full text-center text-gray-500 py-10">Tidak ada produk ditemukan.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
</div>