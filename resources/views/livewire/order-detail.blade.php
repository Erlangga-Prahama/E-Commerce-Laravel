<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-1">Pesanan #{{ $order->id }}</h1>
    <p class="text-sm text-gray-500 mb-6">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>

    <div class="bg-white rounded shadow p-4 mb-4">
        <span class="text-sm px-3 py-1 rounded bg-blue-100 text-blue-700">{{ $order->status->label() }}</span>
    </div>

    <div class="bg-white rounded shadow p-4 mb-4">
        <h2 class="font-bold mb-3">Alamat Pengiriman</h2>
        <p class="text-sm">{{ $order->address->recipient_name }} — {{ $order->address->phone }}</p>
        <p class="text-sm text-gray-600">{{ $order->address->address_line }}, {{ $order->address->city }}, {{ $order->address->province }} {{ $order->address->postal_code }}</p>
    </div>

    <div class="bg-white rounded shadow p-4">
        <h2 class="font-bold mb-3">Produk</h2>
        @foreach ($order->items as $item)
            <div class="flex justify-between text-sm py-1">
                <span>{{ $item->product_name }} x{{ $item->quantity }}</span>
                <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
        @endforeach
        <div class="flex justify-between text-sm py-1 border-t mt-2 pt-2">
            <span>Subtotal</span>
            <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm py-1">
            <span>Ongkos Kirim</span>
            <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between font-bold py-1 border-t mt-2 pt-2">
            <span>Total</span>
            <span>Rp{{ number_format($order->total, 0, ',', '.') }}</span>
        </div>
    </div>
</div>