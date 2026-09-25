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

            {{-- Tombol "Tambah ke Keranjang" akan kita isi di Fase 7 --}}
        </div>
    </div>
</div>