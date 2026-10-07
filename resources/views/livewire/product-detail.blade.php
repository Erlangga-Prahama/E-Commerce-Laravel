<div class="max-w-4xl mx-auto">
    <a href="{{ route('products.index') }}" class="text-sm text-muted hover:text-ink">Kembali ke katalog</a>

    <div class="grid md:grid-cols-2 gap-10 mt-4">
        <div>
            @php $images = $product->images; @endphp
            <div class="aspect-square bg-line/30 rounded-sm overflow-hidden">
                @if ($images->isNotEmpty())
                    <img src="{{ Storage::url($images->first()->path) }}" class="w-full h-full object-cover">
                @endif
            </div>
            @if ($images->count() > 1)
                <div class="flex gap-2 mt-3">
                    @foreach ($images as $image)
                        <div class="w-16 h-16 rounded-sm overflow-hidden border border-line">
                            <img src="{{ Storage::url($image->path) }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <span class="badge bg-primary/5 text-primary">{{ $product->category->name }}</span>
            <h1 class="font-display text-2xl font-bold mt-2">{{ $product->name }}</h1>
            <p class="font-display text-3xl font-bold mt-3">Rp{{ number_format($product->price, 0, ',', '.') }}</p>

            <p class="text-sm mt-2">
                @if ($product->stock > 0)
                    <span class="text-success">Stok tersedia ({{ $product->stock }})</span>
                @else
                    <span class="text-danger">Stok habis</span>
                @endif
            </p>

            <p class="mt-5 text-sm text-ink/80 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>

            @if (session('cart-message'))
                <div class="border border-success/30 bg-success/5 text-success text-sm px-4 py-2.5 rounded-sm mt-5">
                    {{ session('cart-message') }}
                </div>
            @endif

            @if ($product->stock > 0)
                <form wire:submit="addToCart" class="flex items-center gap-3 mt-5">
                    <input type="number" wire:model="quantity" min="1" max="{{ $product->stock }}" class="input w-20">
                    <button type="submit" class="btn-primary">Tambah ke Keranjang</button>
                </form>
                @error('quantity') <span class="text-danger text-sm">{{ $message }}</span> @enderror
            @endif
        </div>
    </div>
</div>