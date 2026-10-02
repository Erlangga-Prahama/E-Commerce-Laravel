<div>
    <h1 class="text-2xl font-bold mb-6">Riwayat Pesanan</h1>

    @forelse ($orders as $order)
        <a href="{{ route('orders.show', $order) }}" wire:key="order-{{ $order->id }}" class="block bg-white rounded shadow p-4 mb-3 hover:shadow-md">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-medium">Pesanan #{{ $order->id }}</p>
                    <p class="text-sm text-gray-500">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
                    <p class="text-sm text-gray-500">{{ $order->items->count() }} produk</p>
                </div>
                <div class="text-right">
                    <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">{{ $order->status->label() }}</span>
                    <p class="font-bold mt-1">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </a>
    @empty
        <p class="text-gray-500 py-10 text-center">Belum ada pesanan.</p>
    @endforelse

    <div class="mt-4">{{ $orders->links() }}</div>
</div>