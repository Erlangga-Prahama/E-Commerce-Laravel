<?php

namespace App\Livewire\Admin\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;

#[Layout('layouts.admin')]
class OrderManager extends Component
{
    use WithPagination;

    public ?int $filterStatus = null;

    public function mount(): void
    {
        $this->authorize('viewAny', Order::class);
    }

    public function changeStatus(Order $order, string $newStatus, OrderService $orderService): void
    {
        $this->authorize('updateStatus', $order);

        try {
            $orderService->updateStatus($order, OrderStatus::from($newStatus));
        } catch (RuntimeException $e) {
            $this->addError('status-' . $order->id, $e->getMessage());
        }
    }

    public function render()
    {
        $orders = Order::query()
            ->with(['user', 'items'])
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.orders.order-manager', [
            'orders' => $orders,
            'statuses' => OrderStatus::cases(),
        ]);
    }
}