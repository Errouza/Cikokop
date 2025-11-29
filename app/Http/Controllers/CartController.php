<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        return view('cart');
    }

    public function updateQuantity(Request $request)
    {
        $id = $request->input('id');
        $delta = $request->input('delta', 0);

        $cart = Session::get('cart', []);
        $item = collect($cart)->firstWhere('id', $id);

        if ($item) {
            $newQty = ($item['qty'] ?? 0) + $delta;
            $newItemTotal = 0;
            $removed = false;

            if ($newQty <= 0) {
                // Auto-remove if qty reaches 0
                $cart = array_filter($cart, function ($i) use ($id) {
                    return $i['id'] != $id;
                });
                $cart = array_values($cart);
                $removed = true;
            } else {
                // Update qty
                $cart = array_map(function ($i) use ($id, $newQty) {
                    return $i['id'] == $id ? array_merge($i, ['qty' => $newQty]) : $i;
                }, $cart);
                $newItemTotal = $item['price'] * $newQty;
            }
            Session::put('cart', $cart);

            // Calculate new cart total
            $cartTotal = array_sum(array_map(function ($i) {
                return $i['price'] * ($i['qty'] ?? 0);
            }, $cart));

            return response()->json([
                'success' => true,
                'new_quantity' => $removed ? 0 : $newQty,
                'new_item_total' => $newItemTotal,
                'cart_total' => $cartTotal,
                'removed' => $removed
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function remove(Request $request)
    {
        $id = $request->input('id');
        $cart = Session::get('cart', []);
        $cart = array_filter($cart, function ($item) use ($id) {
            return $item['id'] != $id;
        });
        $cart = array_values($cart);
        Session::put('cart', $cart);

        // Calculate new cart total
        $cartTotal = array_sum(array_map(function ($i) {
            return $i['price'] * ($i['qty'] ?? 0);
        }, $cart));

        return response()->json([
            'success' => true,
            'cart_total' => $cartTotal,
            'removed' => true
        ]);
    }
}
