<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Kelola Pesanan</h1>
        <select wire:model.live="filterStatus" class="border rounded p-2">
            <option value="">Semua Status</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>

    <table class="w-full bg-white shadow rounded">
        <thead>
            <tr class="border-b text-left">
                <th class="p-3">ID</th>
                <th class="p-3">Customer</th>
                <th class="p-3">Total</th>
                <th class="p-3">Status</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr class="border-b" wire:key="order-{{ $order->id }}">
                    <td class="p-3">
                        <a href="{{ route('orders.show', $order) }}" class="text-blue-600">#{{ $order->id }}</a>
                    </td>
                    <td class="p-3">{{ $order->user->name }}</td>
                    <td class="p-3">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="p-3">{{ $order->status->label() }}</td>
                    <td class="p-3">
                        @error('status-' . $order->id) <span class="text-red-600 text-xs block">{{ $message }}</span> @enderror
                        <div class="flex gap-1 flex-wrap">
                            @foreach ($order->status->allowedTransitions() as $target)
                                <button
                                    wire:click="changeStatus({{ $order->id }}, '{{ $target->value }}')"
                                    wire:confirm="Ubah status ke {{ $target->label() }}?"
                                    class="text-xs px-2 py-1 border rounded hover:bg-gray-50"
                                >
                                    → {{ $target->label() }}
                                </button>
                            @endforeach
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $orders->links() }}</div>
</div>