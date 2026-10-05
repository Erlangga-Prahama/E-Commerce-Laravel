<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Checkout;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_complete_checkout_and_stock_decreases(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 50000, 'stock' => 10]);
        $address = Address::factory()->for($user)->create();

        $cart = Cart::factory()->for($user)->create();
        $cart->items()->create(['product_id' => $product->id, 'quantity' => 3]);

        Livewire::actingAs($user)
            ->test(Checkout::class)
            ->set('selectedAddressId', $address->id)
            ->call('placeOrder')
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'address_id' => $address->id,
            'subtotal' => 150000,
        ]);

        $this->assertEquals(7, $product->fresh()->stock);
        $this->assertEquals(0, $cart->fresh()->items()->count());
    }

    public function test_checkout_fails_when_stock_insufficient(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 1]);
        $address = Address::factory()->for($user)->create();

        $cart = Cart::factory()->for($user)->create();
        $cart->items()->create(['product_id' => $product->id, 'quantity' => 5]);

        Livewire::actingAs($user)
            ->test(Checkout::class)
            ->set('selectedAddressId', $address->id)
            ->call('placeOrder')
            ->assertHasErrors('order');

        $this->assertDatabaseCount('orders', 0);
        $this->assertEquals(1, $product->fresh()->stock);
    }

    public function test_cannot_checkout_with_empty_cart(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->for($user)->create();
        Cart::factory()->for($user)->create();

        Livewire::actingAs($user)
            ->test(Checkout::class)
            ->set('selectedAddressId', $address->id)
            ->call('placeOrder')
            ->assertHasErrors('order');

        $this->assertDatabaseCount('orders', 0);
    }
}