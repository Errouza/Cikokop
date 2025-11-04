<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;

Route::get('/menu', [OrderController::class, 'menu'])->name('menu');
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/payment/{order}', [OrderController::class, 'payment'])->name('payment.show');
Route::post('/payment/{order}/confirm', [OrderController::class, 'confirmPayment'])->name('payment.confirm');
Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
// Admin - create menu item (not linked in UI)
Route::get('/admin/menus/create', [MenuController::class, 'create'])->name('admin.menu.create');
Route::post('/admin/menus', [MenuController::class, 'store'])->name('admin.menu.store');
// Admin listings
Route::get('/admin/menus', [MenuController::class, 'index'])->name('admin.menu.index');
Route::get('/admin/orders', [OrderController::class, 'adminIndex'])->name('admin.orders.index');
