<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\OrderController;

Route::get('/menu', [OrderController::class, 'menu'])->name('menu');
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/payment/{order}', [OrderController::class, 'payment'])->name('payment.show');
Route::post('/payment/{order}/confirm', [OrderController::class, 'confirmPayment'])->name('payment.confirm');
