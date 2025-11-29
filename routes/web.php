<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;

Route::get('/menu', function () {
    $menus = Menu::orderBy('id', 'asc')->get();
    return view('menu', compact('menus'));
})->name('menu');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::post('/cart/update-customization', [CartController::class, 'updateCustomization'])->name('cart.updateCustomization');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
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
// Menu CRUD
Route::get('/admin/menus/{menu}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
Route::match(['put','patch'], '/admin/menus/{menu}', [MenuController::class, 'update'])->name('admin.menu.update');
Route::delete('/admin/menus/{menu}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');

// Magic recommender
Route::get('/magic', [MenuController::class, 'magic'])->name('magic');
