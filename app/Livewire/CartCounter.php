<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;

class CartCounter extends Component
{
    public int $count = 0;

    public function mount(CartService $cartService): void
    {
        $this->count = $cartService->currentCart()->totalItems();
    }

    #[On('cart-updated')]
    public function refreshCount(CartService $cartService): void
    {
        $this->count = $cartService->currentCart()->totalItems();
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
