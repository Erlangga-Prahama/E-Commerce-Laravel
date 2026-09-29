<?php

namespace App\Listeners;

use App\Services\CartService;
use Illuminate\Auth\Events\Login;

class MergeCartOnLogin
{
    public function __construct(private CartService $cartService) {}

    public function handle(Login $event): void
    {
        $this->cartService->mergeGuestCartIntoUser($event->user);
    }
}