<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use RuntimeException;

class PaymentService
{
    public function __construct(private OrderService $orderService) {}

    public function simulateSuccess(Order $order): void
    {
        $payment = $order->payment;

        if (! $payment || $payment->status !== PaymentStatus::Pending) {
            throw new RuntimeException('Pesanan ini tidak dalam status menunggu pembayaran.');
        }

        $payment->update([
            'status' => PaymentStatus::Success,
            'reference' => 'DUMMY-' . strtoupper(uniqid()),
            'paid_at' => now(),
        ]);

        $this->orderService->updateStatus($order, OrderStatus::Paid);
    }

    public function simulateFailure(Order $order): void
    {
        $payment = $order->payment;

        if (! $payment || $payment->status !== PaymentStatus::Pending) {
            throw new RuntimeException('Pesanan ini tidak dalam status menunggu pembayaran.');
        }

        $payment->update(['status' => PaymentStatus::Failed]);
    }
}