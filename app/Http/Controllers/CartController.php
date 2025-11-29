<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $price = $request->input('price');
        $image = $request->input('image');
        $quantity = $request->input('quantity', 1);
        $notes = $request->input('notes', '');
        $ice = $request->input('ice', 'normal');
        $sugar = $request->input('sugar', 'normal');

        // Get current cart from session using standardized 'cart' key
        $cart = session()->get('cart', []);

        // Check if item already exists
        if (isset($cart[$id])) {
            // Update existing item
            $cart[$id]['quantity'] += $quantity;
            $cart[$id]['notes'] = $notes;
            $cart[$id]['ice'] = $ice;
            $cart[$id]['sugar'] = $sugar;
        } else {
            // Add new item
            $cart[$id] = [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'image' => $image,
                'quantity' => $quantity,
                'notes' => $notes,
                'ice' => $ice,
                'sugar' => $sugar
            ];
        }

        // Save back to session using standardized 'cart' key
        session()->put('cart', $cart);

        // Calculate total items for response
        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'total_items' => $totalItems,
            'message' => 'Item added to cart successfully'
        ]);
    }

    public function index()
    {
        // 1. Get cart from session, default to empty array if null
        $cart = session()->get('cart', []);

        // 2. Calculate Total immediately to pass to view
        $total = 0;
        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // 3. Return View with debug data
        return view('cart', compact('cart', 'total'));
    }

    public function updateQuantity(Request $request)
    {
        $id = $request->input('id');
        $delta = $request->input('delta', 0);

        // Get cart from session using standardized 'cart' key
        $cart = session()->get('cart', []);
        
        // Check if item exists
        if (!isset($cart[$id])) {
            return response()->json(['success' => false]);
        }

        $item = $cart[$id];
        $newQty = ($item['quantity'] ?? 0) + $delta;
        $newItemTotal = 0;
        $removed = false;

        if ($newQty <= 0) {
            // Auto-remove if qty reaches 0
            unset($cart[$id]);
            $removed = true;
        } else {
            // Update qty
            $cart[$id]['quantity'] = $newQty;
            $newItemTotal = $item['price'] * $newQty;
        }

        // Save back to session using standardized 'cart' key
        session()->put('cart', $cart);

        // Calculate new cart total
        $cartTotal = 0;
        foreach ($cart as $cartItem) {
            $cartTotal += $cartItem['price'] * ($cartItem['quantity'] ?? 0);
        }

        return response()->json([
            'success' => true,
            'new_quantity' => $removed ? 0 : $newQty,
            'new_item_total' => $newItemTotal,
            'cart_total' => $cartTotal,
            'removed' => $removed
        ]);
    }

    public function updateCustomization(Request $request)
    {
        $id = $request->input('id');
        $ice = $request->input('ice', 'normal');
        $sugar = $request->input('sugar', 'normal');

        // Get cart from session using standardized 'cart' key
        $cart = session()->get('cart', []);
        
        // Check if item exists
        if (!isset($cart[$id])) {
            return response()->json(['success' => false]);
        }

        // Update the item's customization
        $notes = "Es: " . ($ice === 'less' ? 'Sedikit' : ($ice === 'no' ? 'Tidak Ada' : 'Normal')) . 
                ", Gula: " . ($sugar === 'less' ? 'Sedikit' : ($sugar === 'no' ? 'Tanpa Gula' : 'Normal'));
        
        $cart[$id]['ice'] = $ice;
        $cart[$id]['sugar'] = $sugar;
        $cart[$id]['notes'] = $notes;
        
        // Save back to session using standardized 'cart' key
        session()->put('cart', $cart);

        // Calculate new cart total
        $cartTotal = 0;
        foreach ($cart as $cartItem) {
            $cartTotal += $cartItem['price'] * ($cartItem['quantity'] ?? 0);
        }

        return response()->json([
            'success' => true,
            'notes' => $notes,
            'cart_total' => $cartTotal
        ]);
    }

    public function remove(Request $request)
    {
        $id = $request->input('id');

        // Get cart from session using standardized 'cart' key
        $cart = session()->get('cart', []);
        
        // Remove item if exists
        if (isset($cart[$id])) {
            unset($cart[$id]);
            
            // Save back to session using standardized 'cart' key
            session()->put('cart', $cart);

            // Calculate new cart total
            $cartTotal = 0;
            foreach ($cart as $cartItem) {
                $cartTotal += $cartItem['price'] * ($cartItem['quantity'] ?? 0);
            }

            return response()->json([
                'success' => true,
                'cart_total' => $cartTotal,
                'removed' => true
            ]);
        }

        return response()->json(['success' => false]);
    }
}
