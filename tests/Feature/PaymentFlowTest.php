<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Livewire\PaymentSimulator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_simulate_success_updates_order_and_payment_status(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create(['status' => 'pending']);
        Payment::factory()->for($order)->create(['status' => 'pending']);

        Livewire::actingAs($user)
            ->test(PaymentSimulator::class, ['order' => $order])
            ->call('paySuccess')
            ->assertRedirect();

        $this->assertEquals('paid', $order->fresh()->status->value);
        $this->assertEquals('success', $order->payment->fresh()->status->value);
        $this->assertNotNull($order->payment->fresh()->reference);
    }

    public function test_cannot_pay_already_paid_order(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create(['status' => 'paid']);
        Payment::factory()->for($order)->create(['status' => 'success']);

        Livewire::actingAs($user)
            ->test(PaymentSimulator::class, ['order' => $order])
            ->call('paySuccess')
            ->assertHasErrors('payment');

        $this->assertEquals('paid', $order->fresh()->status->value);
    }

    public function test_user_cannot_pay_for_others_order(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $order = Order::factory()->for($owner)->create();

        Livewire::actingAs($intruder)
            ->test(PaymentSimulator::class, ['order' => $order])
            ->assertForbidden();
    }
}