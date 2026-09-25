<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Categories\CategoryManager;
use App\Livewire\Admin\Products\ProductManager;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/admin-test', fn () => 'kamu admin!')->middleware(['auth', 'admin']);

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/categories', CategoryManager::class)->name('categories.index');
    Route::get('/products', ProductManager::class)->name('products.index');
});

require __DIR__.'/auth.php';
