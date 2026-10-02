<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use RuntimeException;

class OrderService
{
    public function updateStatus(Order $order, OrderStatus $newStatus): void
    {
        if (!$order->status->canTransitionTo($newStatus)) {
            throw new RuntimeException(
                "Tidak bisa ubah status dari {$order->status->label()} ke {$newStatus->label}."
            );
        }

        $order->update(['status' => $newStatus]);
    }
}