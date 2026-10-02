<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class OrderHistory extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items')->latest()->paginate(10);

        return view('livewire.order-history', ['orders' => $orders]);
    }
}
