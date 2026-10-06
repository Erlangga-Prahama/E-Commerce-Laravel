<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_item_clamps_quantity_to_available_stock(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create(['stock' => 3]);
        $service = app(CartService::class);

        $service->addItem($product, 10);

        $cart = $service->currentCart();
        $this->assertEquals(3, $cart->items->first()->quantity);
    }

    public function test_adding_same_product_twice_increments_quantity_not_duplicates_row(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create(['stock' => 10]);
        $service = app(CartService::class);

        $service->addItem($product, 2);
        $service->addItem($product, 3);

        $cart = $service->currentCart();
        $this->assertCount(1, $cart->items);
        $this->assertEquals(5, $cart->items->first()->quantity);
    }

    public function test_merge_guest_cart_combines_quantities(): void
    {
        $product = Product::factory()->create(['stock' => 20]);
        $service = app(CartService::class);

        // simulasikan guest menambah item (tanpa login)
        $service->addItem($product, 2);

        $user = User::factory()->create();
        $service->mergeGuestCartIntoUser($user);

        $this->actingAs($user);
        $userCart = $service->currentCart();

        $this->assertEquals(2, $userCart->items->first()->quantity);
    }
}