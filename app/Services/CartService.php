<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class CartService
{
    private const GUEST_COOKIE = 'cart_guest_token';

    public function currentCart(): Cart
    {
        $user = auth()->user();

        if ($user) {
            return Cart::firstOrCreate(['user_id' => $user->id]);
        }

        $token = Cookie::get(self::GUEST_COOKIE);

        if (! $token) {
            $token = Str::uuid()->toString();
            Cookie::queue(self::GUEST_COOKIE, $token, 60 * 24 * 30); // 30 hari
        }

        return Cart::firstOrCreate(['guest_token' => $token]);
    }

    public function addItem(Product $product, int $quantity = 1): void
    {
        $cart = $this->currentCart();
        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $item->quantity = ($item->exists ? $item->quantity : 0) + $quantity;
        $item->quantity = min($item->quantity, $product->stock);
        $item->save();
    }

    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        $item = $this->currentCart()->items()->findOrFail($cartItemId);

        if ($quantity < 1) {
            $item->delete();
            return;
        }

        $item->update(['quantity' => min($quantity, $item->product->stock)]);
    }

    public function removeItem(int $cartItemId): void
    {
        $this->currentCart()->items()->findOrFail($cartItemId)->delete();
    }

    public function mergeGuestCartIntoUser(User $user): void
    {
        $token = Cookie::get(self::GUEST_COOKIE);

        if (! $token) {
            return;
        }

        $guestCart = Cart::where('guest_token', $token)->first();

        if (! $guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($guestCart->items as $guestItem) {
            $existing = $userCart->items()->where('product_id', $guestItem->product_id)->first();

            if ($existing) {
                $existing->update([
                    'quantity' => min($existing->quantity + $guestItem->quantity, $guestItem->product->stock),
                ]);
            } else {
                $userCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'quantity' => $guestItem->quantity,
                ]);
            }
        }

        $guestCart->delete();
        Cookie::queue(Cookie::forget(self::GUEST_COOKIE));
    }
}