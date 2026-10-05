<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\PaymentService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use RuntimeException;

#[Layout('layouts.app')]
class PaymentSimulator extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $this->authorize('view', $order);
        $this->order = $order->load('payment');
    }

    public function paySuccess(PaymentService $paymentService)
    {
        try {
            $paymentService->simulateSuccess($this->order);
        } catch (RuntimeException $e) {
            $this->addError('payment', $e->getMessage());
            return;
        }

        return redirect()->route('orders.show', $this->order);
    }

    public function payFailed(PaymentService $paymentService)
    {
        try {
            $paymentService->simulateFailure($this->order);
        } catch (RuntimeException $e) {
            $this->addError('payment', $e->getMessage());
            return;
        }

        $this->order->refresh();
    }

    public function render()
    {
        return view('livewire.payment-simulator');
    }
}