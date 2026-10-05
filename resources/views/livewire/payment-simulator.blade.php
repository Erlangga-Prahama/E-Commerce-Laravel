<div class="max-w-md mx-auto text-center">
    <h1 class="text-2xl font-bold mb-2">Pembayaran Pesanan #{{ $order->id }}</h1>
    <p class="text-gray-600 mb-6">Total: <span class="font-bold">Rp{{ number_format($order->total, 0, ',', '.') }}</span></p>

    @error('payment') <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">{{ $message }}</div> @enderror

    @if ($order->payment?->status->value === 'pending')
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-4">
            <p class="text-sm text-yellow-700 mb-3">
                Ini simulasi pembayaran — belum terhubung payment gateway asli.
            </p>
            <div class="flex gap-2 justify-center">
                <button wire:click="paySuccess" class="bg-green-600 text-white px-4 py-2 rounded">
                    Simulasikan Bayar Berhasil
                </button>
                <button wire:click="payFailed" wire:confirm="Simulasikan pembayaran gagal?" class="bg-red-600 text-white px-4 py-2 rounded">
                    Simulasikan Gagal
                </button>
            </div>
        </div>
    @elseif ($order->payment?->status->value === 'success')
        <p class="text-green-600">Pembayaran berhasil. Ref: {{ $order->payment->reference }}</p>
    @elseif ($order->payment?->status->value === 'failed')
        <p class="text-red-600">Pembayaran gagal. Silakan hubungi admin atau buat pesanan baru.</p>
    @endif

    <a href="{{ route('orders.show', $order) }}" class="block mt-4 text-sm text-blue-600">Lihat Detail Pesanan</a>
</div>