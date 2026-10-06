<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\User;
use App\Livewire\Admin\Orders\OrderManager;
use App\Livewire\OrderDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OrderAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_admin_order_manager(): void
    {
        $customer = User::factory()->create(['role' => UserRole::Customer]);

        Livewire::actingAs($customer)
            ->test(OrderManager::class)
            ->assertForbidden();
    }

    public function test_admin_can_access_order_manager(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        Livewire::actingAs($admin)
            ->test(OrderManager::class)
            ->assertOk();
    }

    public function test_customer_can_view_own_order_but_not_others(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $order = Order::factory()->for($owner)->create();

        Livewire::actingAs($owner)
            ->test(OrderDetail::class, ['order' => $order])
            ->assertOk();

        Livewire::actingAs($intruder)
            ->test(OrderDetail::class, ['order' => $order])
            ->assertForbidden();
    }

    public function test_invalid_status_transition_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $order = Order::factory()->create(['status' => 'pending']);

        // pending -> shipped seharusnya tidak valid (harus lewat paid dulu)
        Livewire::actingAs($admin)
            ->test(OrderManager::class)
            ->call('changeStatus', $order, 'shipped')
            ->assertHasErrors('status-' . $order->id);

        $this->assertEquals('pending', $order->fresh()->status->value);
    }
}