<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    @error('order') <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ $message }}</div> @enderror
    @error('address') <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ $message }}</div> @enderror

    <div class="bg-white rounded shadow p-4 mb-4">
        <h2 class="font-bold mb-3">Alamat Pengiriman</h2>
        @forelse ($addresses as $address)
            <label class="flex items-start gap-2 border rounded p-3 mb-2 cursor-pointer" wire:key="addr-{{ $address->id }}">
                <input type="radio" wire:model="selectedAddressId" value="{{ $address->id }}" class="mt-1">
                <div class="text-sm">
                    <p class="font-medium">{{ $address->recipient_name }} — {{ $address->phone }}</p>
                    <p class="text-gray-600">{{ $address->address_line }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                </div>
            </label>
        @empty
            <p class="text-sm text-gray-500">Belum ada alamat. <a href="{{ route('addresses.index') }}" class="text-blue-600">Tambah alamat</a> dulu.</p>
        @endforelse
    </div>

    <div class="bg-white rounded shadow p-4 mb-4">
        <h2 class="font-bold mb-3">Ringkasan Pesanan</h2>
        @foreach ($cart->items as $item)
            <div class="flex justify-between text-sm py-1">
                <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                <span>Rp{{ number_format($item->subtotal(), 0, ',', '.') }}</span>
            </div>
        @endforeach
        <div class="flex justify-between text-sm py-1 border-t mt-2 pt-2">
            <span>Subtotal</span>
            <span>Rp{{ number_format($cart->subtotal(), 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm py-1">
            <span>Ongkos Kirim</span>
            <span>Rp{{ number_format($shippingCost, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between font-bold py-1 border-t mt-2 pt-2">
            <span>Total</span>
            <span>Rp{{ number_format($cart->subtotal() + $shippingCost, 0, ',', '.') }}</span>
        </div>
    </div>

    <button wire:click="placeOrder" wire:loading.attr="disabled" class="w-full bg-blue-600 text-white py-3 rounded font-medium">
        Buat Pesanan
    </button>
</div>