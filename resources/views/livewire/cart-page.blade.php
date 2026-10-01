<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

    @forelse ($cart->items as $item)
        <div class="flex items-center gap-4 border-b py-4" wire:key="cart-item-{{ $item->id }}">
            <img src="{{ $item->product->images->first() ? \Storage::url($item->product->images->first()->path) : '' }}" class="w-16 h-16 object-cover rounded bg-gray-100">

            <div class="flex-1">
                <p class="font-medium">{{ $item->product->name }}</p>
                <p class="text-sm text-gray-500">Rp{{ number_format($item->product->price, 0, ',', '.') }}</p>
            </div>

            <input
                type="number"
                value="{{ $item->quantity }}"
                min="0"
                max="{{ $item->product->stock }}"
                wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                class="w-16 border rounded p-1 text-center"
            >

            <p class="w-28 text-right font-medium">Rp{{ number_format($item->subtotal(), 0, ',', '.') }}</p>

            <button wire:click="removeItem({{ $item->id }})" class="text-red-600 text-sm">Hapus</button>
        </div>
    @empty
        <p class="text-gray-500 py-10 text-center">Keranjang kosong.</p>
    @endforelse

    @if ($cart->items->isNotEmpty())
        <div class="flex justify-between items-center mt-6 text-lg font-bold">
            <span>Subtotal</span>
            <span>Rp{{ number_format($cart->subtotal(), 0, ',', '.') }}</span>
        </div>

        {{-- <a href="{{ route('checkout.index') }}" class="block text-center bg-blue-600 text-white py-3 rounded mt-4">
            Lanjut ke Checkout
        </a> --}}
    @endif
</div>