<div class="max-w-4xl mx-auto">
    <a href="{{ route('products.index') }}" class="text-sm text-blue-600">&larr; Kembali ke Katalog</a>

    <div class="grid md:grid-cols-2 gap-8 mt-4">
        <div>
            @php $images = $product->images; @endphp
            @if ($images->isNotEmpty())
                <img src="{{ Storage::url($images->first()->path) }}" class="w-full rounded shadow">
                <div class="flex gap-2 mt-2">
                    @foreach ($images as $image)
                        <img src="{{ Storage::url($image->path) }}" class="w-16 h-16 object-cover rounded border">
                    @endforeach
                </div>
            @else
                <div class="w-full h-80 bg-gray-200 rounded"></div>
            @endif
        </div>

        <div>
            <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
            <h1 class="text-2xl font-bold mt-1">{{ $product->name }}</h1>
            <p class="text-2xl text-blue-600 font-bold mt-3">
                Rp{{ number_format($product->price, 0, ',', '.') }}
            </p>
            <p class="text-sm text-gray-500 mt-1">
                Stok: {{ $product->stock > 0 ? $product->stock : 'Habis' }}
            </p>

            <p class="mt-4 text-gray-700 whitespace-pre-line">{{ $product->description }}</p>

            @if (session('cart-message'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-3 text-sm">{{ session('cart-message') }}</div>
            @endif

            @if ($product->stock > 0)
                <form wire:submit="addToCart" class="flex items-center gap-2 mt-4">
                    <input type="number" wire:model="quantity" min="1" max="{{ $product->stock }}" class="w-20 border rounded p-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Tambah ke Keranjang</button>
                </form>
                @error('quantity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            @else
                <p class="text-red-600 mt-4">Stok habis</p>
            @endif
        </div>
    </div>
</div>