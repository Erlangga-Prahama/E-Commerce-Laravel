<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'address_id' => Address::factory(),
            'status' => 'pending'  ,
            'subtotal' => 100000,
            'shipping_cost' => 15000,
            'total' => 115000,
        ];
    }
}