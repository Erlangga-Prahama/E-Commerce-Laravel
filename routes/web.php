<?php

use App\Livewire\Actions\Logout;
use App\Livewire\AddressManager;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Categories\CategoryManager;
use App\Livewire\Admin\Orders\OrderManager;
use App\Livewire\Admin\Products\ProductManager;
use App\Livewire\ProductCatalog;
use App\Livewire\ProductDetail;
use App\Livewire\CartPage;
use App\Livewire\Checkout;
use App\Livewire\OrderDetail;
use App\Livewire\OrderHistory;
use App\Livewire\PaymentSimulator;
use App\Models\Payment;
use Illuminate\Http\Request;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/logout', function(Request $request, Logout $logout){
    $logout();

    return redirect('/');
})->name('logout')->middleware('auth');

Route::get('/admin-test', fn () => 'kamu admin!')->middleware(['auth', 'admin']);

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/categories', CategoryManager::class)->name('categories.index');
    Route::get('/products', ProductManager::class)->name('products.index');
    Route::get('/addresses', AddressManager::class)->name('addresses.index');
    Route::get('/checkout', Checkout::class)->name('checkout.index');
    Route::get('/orders', OrderManager::class)->name('orders.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/addresses', AddressManager::class)->name('addresses.index');
    Route::get('/checkout', Checkout::class)->name('checkout.index');
    Route::get('/orders', OrderHistory::class)->name('orders.index');
    Route::get('/orders/{order}', OrderDetail::class)->name('orders.show');
    Route::get('/orders/{order}/pay', PaymentSimulator::class)->name('payment.pay');
});

Route::get('/products', ProductCatalog::class)->name('products.index');
Route::get('/products/{product}', ProductDetail::class)->name('products.show');
Route::get('/cart', CartPage::class)->name('cart.index');
require __DIR__.'/auth.php';
