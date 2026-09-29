<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class ProductDetail extends Component
{
    public Product $product;

    #[Validate('required|integer|min:1')]
    public int $quantity = 1;

    public function mount(Product $product): void
    {
        abort_unless($product->is_active, 404);

        $this->product = $product->load(['category', 'images']);
    }

    public function addToCart(CartService $cartService): void
    {
        $this->validate();

        if ($this->quantity > $this->product->stock) {
            $this->addError('quantity', 'Jumlah melebihi stok tersedia.');
            return;
        }

        $cartService->addItem($this->product, $this->quantity);
        $this->dispatch('cart-updated');
        session()->flash('cart-message', 'Produk ditambahkan ke keranjang.');
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}