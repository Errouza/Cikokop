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
    
    // Calculate cart total for footer with price sanitization
    $cart = session()->get('cart', []);
    $total = 0;
    foreach ($cart as $cartItem) {
        // Sanitize price before calculation
        $cleanPrice = 0;
        if (is_numeric($cartItem['price'])) {
            $cleanPrice = (float)$cartItem['price'];
        } else {
            // Remove formatting characters
            $cleanPrice = (float) preg_replace('/[^0-9.]/', '', $cartItem['price'] ?? '0');
        }
        $total += $cleanPrice * ($cartItem['quantity'] ?? 0);
    }
    
    return view('menu', compact('menus', 'total'));
})->name('menu');

Route::get('/cart/clear', function () {
    session()->forget('cart');
    // Also clear any other potential cart-related session data
    session()->forget(['cart_items', 'cart_total', 'temp_cart']);
    return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
})->name('cart.clear');

Route::get('/cart/force-clear', function () {
    // Force clear all session data to eliminate zombie data
    session()->flush();
    return redirect()->route('cart.index')->with('success', 'All session data cleared! Cart is now empty.');
})->name('cart.forceClear');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/status', [CartController::class, 'getCartStatus'])->name('cart.status');
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
Route::post('/admin/orders/{order}/complete', [OrderController::class, 'complete'])->name('admin.orders.complete');
Route::post('/admin/orders/{order}/confirm-payment', [OrderController::class, 'adminConfirmPayment'])->name('admin.orders.confirmPayment');
// Menu CRUD
Route::get('/admin/menus/{menu}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
Route::match(['put','patch'], '/admin/menus/{menu}', [MenuController::class, 'update'])->name('admin.menu.update');
Route::delete('/admin/menus/{menu}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');

// Magic recommender
Route::get('/magic', [MenuController::class, 'magic'])->name('magic');
