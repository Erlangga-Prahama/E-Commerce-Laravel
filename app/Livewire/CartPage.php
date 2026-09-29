<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CartPage extends Component
{
    public function updateQuantity(int $cartItemId, int $quantity, CartService $cartService): void
    {
        $cartService->updateQuantity($cartItemId, $quantity);
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $cartItemId, CartService $cartService): void
    {
        $cartService->removeItem($cartItemId);
        $this->dispatch('cart-updated');
    }

    public function render(CartService $cartService)
    {
        $cart = $cartService->currentCart()->load('items.product.images');

        return view('livewire.cart-page', ['cart' => $cart]);
    }
}