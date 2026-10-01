<?php

namespace App\Livewire;

use App\Models\Address;
use App\Services\CartService;
use App\Services\CheckoutService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use RuntimeException;

#[Layout('layouts.app')]
class Checkout extends Component
{
    public ?int $selectedAddressId = null;

    public function mount(): void
    {
        $default = auth()->user()->addresses()->where('is_default', true)->first();
        $this->selectedAddressId = $default?->id ?? auth()->user()->addresses()->first()?->id;
    }

    public function placeOrder(CartService $cartService, CheckoutService $checkoutService)
    {
        if (! $this->selectedAddressId) {
            $this->addError('address', 'Pilih alamat pengiriman terlebih dahulu.');
            return;
        }

        $address = Address::findOrFail($this->selectedAddressId);
        $this->authorize('update', $address); // pastikan alamat milik user ini

        try {
            $order = $checkoutService->placeOrder($cartService->currentCart(), $address);
        } catch (RuntimeException $e) {
            $this->addError('order', $e->getMessage());
            return;
        }

        $this->dispatch('cart-updated');

        return redirect()->route('orders.show', $order);
    }

    public function render(CartService $cartService, CheckoutService $checkoutService)
    {
        $cart = $cartService->currentCart()->load('items.product');

        return view('livewire.checkout', [
            'cart' => $cart,
            'addresses' => auth()->user()->addresses,
            'shippingCost' => $checkoutService->shippingCost(),
        ]);
    }
}