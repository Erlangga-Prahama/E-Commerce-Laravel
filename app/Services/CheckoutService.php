<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CheckoutService
{
    private const FLAT_SHIPPING_COST = 15000;

    public function shippingCost(): int
    {
        return self::FLAT_SHIPPING_COST;
    }

    public function placeOrder(Cart $cart, Address $address): Order
    {
        if ($cart->items->isEmpty()) {
            throw new RuntimeException('Keranjang kosong.');
        }

        return DB::transaction(function () use ($cart, $address) {
            $cart->load('items.product');
            $subtotal = 0;

            // Lock baris produk yang terlibat untuk cegah race condition stok
            $productIds = $cart->items->pluck('product_id');
            $lockedProducts = \App\Models\Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($cart->items as $item) {
                $product = $lockedProducts[$item->product_id];

                if ($item->quantity > $product->stock) {
                    throw new RuntimeException("Stok {$product->name} tidak mencukupi.");
                }

                $subtotal += $item->quantity * $product->price;
            }

            $shippingCost = $this->shippingCost();

            $order = Order::create([
                'user_id' => $address->user_id,
                'address_id' => $address->id,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $subtotal + $shippingCost,
            ]);

            foreach ($cart->items as $item) {
                $product = $lockedProducts[$item->product_id];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->quantity * $product->price,
                ]);

                $product->decrement('stock', $item->quantity);
            }

            $cart->items()->delete();

            return $order;
        });
    }
}