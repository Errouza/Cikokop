<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $productId = $request->input('id');
        $name = $request->input('name');
        $price = $request->input('price');
        $image = $request->input('image');
        $quantity = $request->input('quantity', 1);
        $notes = $request->input('notes', '');
        $ice = $request->input('ice', 'normal');
        $sugar = $request->input('sugar', 'normal');
        $category = $request->input('category', 'food');  // Add category with default

        // Debug: Log the received price
        \Log::info('addToCart - Raw price received: ' . $price . ' (type: ' . gettype($price) . ')');
        
        // Ensure price is numeric
        $price = is_numeric($price) ? (float)$price : 0;
        
        // Debug: Log the processed price
        \Log::info('addToCart - Processed price: ' . $price . ' (type: ' . gettype($price) . ')');

        // Get current cart from session using standardized 'cart' key
        $cart = session()->get('cart', []);
        
        // Migrate any old structure first
        $cart = $this->migrateCartStructure($cart);

        // Generate unique row ID based on product ID and customization
        $customizationKey = json_encode([$ice, $sugar]);
        $rowId = $productId . '_' . md5($customizationKey);
        
        // Debug: Log the row ID generation
        \Log::info('addToCart - Generated rowId: ' . $rowId . ' for product: ' . $productId . ' with customization: ' . $customizationKey);

        // Check if item with same customization already exists
        if (isset($cart[$rowId])) {
            // Update existing item quantity
            $cart[$rowId]['quantity'] += $quantity;
            $cart[$rowId]['notes'] = $notes;
        } else {
            // Add new item with unique row ID
            $cart[$rowId] = [
                'row_id' => $rowId,
                'product_id' => $productId,
                'name' => $name,
                'price' => $price,  // Already sanitized
                'image' => $image,
                'quantity' => $quantity,
                'notes' => $notes,
                'ice' => $ice,
                'sugar' => $sugar,
                'category' => $category  // Add category
            ];
        }

        // Save back to session using standardized 'cart' key
        session()->put('cart', $cart);

        // Calculate total items for response
        $totalItems = 0;
        foreach ($cart as $cartItem) {
            $quantity = (int)(isset($cartItem['quantity']) ? $cartItem['quantity'] : 0);
            if ($quantity > 0) {
                $totalItems += $quantity;
            }
        }
        
        // Calculate cart total for response with sanitized prices
        $cartTotal = 0;
        foreach ($cart as $cartItem) {
            $sanitizedPrice = $this->sanitizePrice(isset($cartItem['price']) ? $cartItem['price'] : 0);
            $quantity = (int)(isset($cartItem['quantity']) ? $cartItem['quantity'] : 0);
            if ($quantity > 0) {
                $cartTotal += $sanitizedPrice * $quantity;
            }
        }
        
        // Debug: Log the cart contents
        \Log::info('addToCart - Cart contents: ' . json_encode($cart));
        \Log::info('addToCart - Cart total: ' . $cartTotal);

        return response()->json([
            'success' => true,
            'total_items' => $totalItems,
            'total_quantity' => $totalItems,  // For footer display
            'cart_total' => $cartTotal,
            'row_id' => $rowId,  // Return the generated row ID
            'message' => 'Item added to cart successfully'
        ]);
    }

    public function getCartStatus()
    {
        // Get cart from session using standardized 'cart' key
        $cart = session()->get('cart', []);
        
        // Debug: Log the raw cart data
        \Log::info('getCartStatus - Raw cart from session: ' . json_encode($cart));
        
        // Migrate old cart structure if needed
        $cart = $this->migrateCartStructure($cart);
        
        // Clean cart: Remove items with quantity <= 0
        $cleanedCart = [];
        $totalQuantityCount = 0;
        
        foreach($cart as $rowId => $cartItem) {
            $quantity = (int)(isset($cartItem['quantity']) ? $cartItem['quantity'] : 0);
            \Log::info("getCartStatus - Processing item: rowId={$rowId}, name=" . (isset($cartItem['name']) ? $cartItem['name'] : 'N/A') . ", quantity={$quantity}");
            
            if ($quantity > 0) {
                // Sanitize price
                $sanitizedPrice = $this->sanitizePrice(isset($cartItem['price']) ? $cartItem['price'] : 0);
                $cleanedCart[$rowId] = $cartItem;
                $cleanedCart[$rowId]['price'] = $sanitizedPrice;
                $cleanedCart[$rowId]['quantity'] = $quantity;
                
                $totalQuantityCount += $quantity;
                \Log::info("getCartStatus - Kept item: rowId={$rowId}, quantity={$quantity}, running_total={$totalQuantityCount}");
            } else {
                \Log::info("getCartStatus - Removing item with quantity <= 0: rowId={$rowId}, quantity={$quantity}");
            }
        }
        
        // Update session if cleaned
        if ($cleanedCart !== $cart) {
            session()->put('cart', $cleanedCart);
            \Log::info('getCartStatus - Cart cleaned and saved to session');
        }
        $cart = $cleanedCart;
        
        // Calculate cart total with sanitized prices
        $cartTotal = 0;
        foreach ($cart as $rowId => $cartItem) {
            $sanitizedPrice = $this->sanitizePrice(isset($cartItem['price']) ? $cartItem['price'] : 0);
            $quantity = (int)(isset($cartItem['quantity']) ? $cartItem['quantity'] : 0);
            $itemTotal = $sanitizedPrice * $quantity;
            $cartTotal += $itemTotal;
            
            // Debug: Log each item calculation
            \Log::info("getCartStatus - Row {$rowId}: product_id={$cartItem['product_id']}, original_price={$cartItem['price']}, sanitized_price={$sanitizedPrice}, qty={$quantity}, item_total={$itemTotal}");
        }
        
        // Calculate total items using our cleaned count
        $totalItems = $totalQuantityCount;
        
        // Debug: Log final calculations
        \Log::info("getCartStatus - Final calculations: total_items={$totalItems}, cart_total={$cartTotal}");
        
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'cart_total' => $cartTotal,
            'total_items' => $totalItems,
            'total_quantity' => $totalItems  // For footer display
        ]);
    }
    
    private function migrateCartStructure($cart)
    {
        $migratedCart = [];
        
        // Debug: Log input structure
        \Log::info('migrateCartStructure - Input type: ' . gettype($cart));
        \Log::info('migrateCartStructure - Input data: ' . json_encode($cart));
        
        // Check if cart is array (old structure) or object (new structure)
        if (is_array($cart) && isset($cart[0]) && isset($cart[0]['id'])) {
            // Old structure - array of items with 'id' field
            \Log::info('migrateCartStructure - Detected old array structure, converting...');
            
            foreach ($cart as $index => $item) {
                if (!isset($item['id'])) {
                    \Log::info("migrateCartStructure - Skipping item at index {$index} - no 'id' field");
                    continue;
                }
                
                $productId = $item['id'];
                $ice = isset($item['ice']) ? $item['ice'] : 'normal';
                $sugar = isset($item['sugar']) ? $item['sugar'] : 'normal';
                
                // Generate new row ID
                $customizationKey = json_encode([$ice, $sugar]);
                $rowId = $productId . '_' . md5($customizationKey);
                
                // Create new structure
                $migratedCart[$rowId] = [
                    'row_id' => $rowId,
                    'product_id' => $productId,
                    'name' => $item['name'],
                    'price' => is_numeric($item['price']) ? (float)$item['price'] : 0,
                    'image' => isset($item['image']) ? $item['image'] : '',
                    'quantity' => isset($item['qty']) ? $item['qty'] : (isset($item['quantity']) ? $item['quantity'] : 1),
                    'notes' => isset($item['notes']) ? $item['notes'] : '',
                    'ice' => $ice,
                    'sugar' => $sugar
                ];
                
                \Log::info("migrateCartStructure - Migrated old array item[{$index}] -> row_id={$rowId}, product_id={$productId}, qty={$migratedCart[$rowId]['quantity']}");
            }
        } elseif (is_array($cart) && !empty($cart) && !isset($cart[0])) {
            // Already new structure (associative array with row_id keys)
            \Log::info('migrateCartStructure - Already new structure, keeping as is');
            $migratedCart = $cart;
        } else {
            \Log::info('migrateCartStructure - Empty or unrecognized structure, returning empty array');
            $migratedCart = $cart;
        }
        
        // Debug: Log output structure
        \Log::info('migrateCartStructure - Output type: ' . gettype($migratedCart));
        \Log::info('migrateCartStructure - Output count: ' . count($migratedCart));
        \Log::info('migrateCartStructure - Output data: ' . json_encode($migratedCart));
        
        // Save migrated cart back to session if changed
        if ($migratedCart !== $cart) {
            session()->put('cart', $migratedCart);
            \Log::info('migrateCartStructure - Cart structure migrated and saved to session');
        } else {
            \Log::info('migrateCartStructure - No migration needed');
        }
        
        return $migratedCart;
    }

    public function index($clearCart = false)
    {
        // Auto-clear cart if requested
        if ($clearCart || request()->get('clear') === 'true') {
            session()->forget('cart');
            session()->forget(['cart_items', 'cart_total', 'temp_cart']);
            \Log::info('Cart index - Cart auto-cleared');
        }
        
        // 0. Auto-cleanup ghost items
        $cart = session()->get('cart', []);
        \Log::info('Cart index - Initial cart from session: ' . json_encode($cart));
        
        foreach($cart as $key => $item) {
            $quantity = (int)(isset($item['quantity']) ? $item['quantity'] : 0);
            \Log::info("Cart index - Checking item: key={$key}, name=" . (isset($item['name']) ? $item['name'] : 'N/A') . ", quantity={$quantity}");
            
            if($quantity <= 0) {
                unset($cart[$key]); // Remove invalid items
                \Log::info("Cart index - Removed ghost item: key={$key}, name=" . (isset($item['name']) ? $item['name'] : 'N/A') . ", quantity={$quantity}");
            }
        }
        session()->put('cart', $cart); // Save cleaned cart
        
        // 1. Force cleanup any invalid session data
        $this->forceCleanSession();
        
        // 2. Get cart from session, default to empty array if null
        $cart = session()->get('cart', []);
        
        // Debug: Log raw cart data before migration
        \Log::info('Cart index - RAW cart from session BEFORE migration: ' . json_encode($cart));
        
        // Migrate old cart structure if needed
        $cart = $this->migrateCartStructure($cart);
        
        // 3. Clean cart: Remove items with quantity <= 0 (double-check)
        $cleanedCart = [];
        $totalQuantityCount = 0;
        
        foreach($cart as $rowId => $details) {
            $quantity = (int)(isset($details['quantity']) ? $details['quantity'] : 0);
            \Log::info("Cart index - Processing item: rowId={$rowId}, name=" . (isset($details['name']) ? $details['name'] : 'N/A') . ", quantity={$quantity}");
            
            if ($quantity > 0) {
                // Sanitize price to ensure it's numeric
                $sanitizedPrice = $this->sanitizePrice(isset($details['price']) ? $details['price'] : 0);
                $cleanedCart[$rowId] = $details;
                $cleanedCart[$rowId]['price'] = $sanitizedPrice;
                $cleanedCart[$rowId]['quantity'] = $quantity;
                
                $totalQuantityCount += $quantity;
                \Log::info("Cart index - Kept item: rowId={$rowId}, quantity={$quantity}, running_total={$totalQuantityCount}");
            } else {
                \Log::info("Cart index - Removing item with quantity <= 0: rowId={$rowId}, quantity={$quantity}");
            }
        }
        
        // Update session with cleaned cart
        if ($cleanedCart !== $cart) {
            session()->put('cart', $cleanedCart);
            \Log::info('Cart index - Cart cleaned and saved to session');
        }
        
        $cart = $cleanedCart;
        
        // Debug: Log cart data after cleaning
        \Log::info('Cart index - Cart AFTER cleaning: ' . json_encode($cart));
        \Log::info('Cart index - Cart type: ' . gettype($cart));
        \Log::info('Cart index - Cart count: ' . count($cart));
        \Log::info('Cart index - Total quantity calculated: ' . $totalQuantityCount);

        // 4. Calculate Total with sanitized prices
        $total = 0;
        foreach($cart as $rowId => $details) {
            $sanitizedPrice = $this->sanitizePrice(isset($details['price']) ? $details['price'] : 0);
            $quantity = (int)(isset($details['quantity']) ? $details['quantity'] : 0);
            $itemTotal = $sanitizedPrice * $quantity;
            $total += $itemTotal;
            
            // Debug: Log each item calculation
            \Log::info("Cart index - Row {$rowId}: product_id={$details['product_id']}, original_price={$details['price']}, sanitized_price={$sanitizedPrice}, qty={$quantity}, item_total={$itemTotal}");
        }
        
        // Debug: Log the final total
        \Log::info('Cart index - Final total: ' . $total);

        // 5. Return View with clean data
        return view('cart', compact('cart', 'total'));
    }
    
    private function forceCleanSession()
    {
        // Check for and remove any invalid cart data
        $cart = session()->get('cart', []);
        
        // If cart has unexpected structure, clear it
        if (!is_array($cart)) {
            \Log::info('forceCleanSession - Invalid cart structure detected, clearing cart');
            session()->forget('cart');
            return;
        }
        
        // Check for any items with invalid data
        $isValid = true;
        foreach($cart as $rowId => $item) {
            if (!is_array($item) || !isset($item['name']) || !isset($item['price'])) {
                \Log::info("forceCleanSession - Invalid item detected: rowId={$rowId}, removing");
                $isValid = false;
                break;
            }
        }
        
        if (!$isValid) {
            session()->forget('cart');
            \Log::info('forceCleanSession - Cart cleared due to invalid items');
        }
    }
    
    private function sanitizePrice($price)
    {
        // Handle null/empty values
        if (empty($price)) {
            return 0;
        }
        
        // Remove all non-numeric characters (only keep digits)
        $cleanPrice = preg_replace('/[^0-9]/', '', (string)$price);
        
        // Convert to integer
        return (int)($cleanPrice ?: 0);
    }

    public function updateQuantity(Request $request)
    {
        $rowId = $request->input('id');  // This is now row_id, not product_id
        $delta = $request->input('delta', 0);

        // Get cart from session using standardized 'cart' key
        $cart = session()->get('cart', []);
        
        // Check if item exists by row_id
        if (!isset($cart[$rowId])) {
            return response()->json(['success' => false]);
        }

        $item = $cart[$rowId];
        $newQty = (isset($item['quantity']) ? $item['quantity'] : 0) + $delta;
        $newItemTotal = 0;
        $removed = false;

        if ($newQty <= 0) {
            // Auto-remove if qty reaches 0
            unset($cart[$rowId]);
            $removed = true;
        } else {
            // Update qty
            $cart[$rowId]['quantity'] = $newQty;
            $sanitizedPrice = $this->sanitizePrice(isset($item['price']) ? $item['price'] : 0);
            $newItemTotal = $sanitizedPrice * $newQty;
        }

        // Save back to session using standardized 'cart' key
        session()->put('cart', $cart);

        // Calculate new cart total with sanitized prices
        $cartTotal = 0;
        foreach ($cart as $cartItem) {
            $sanitizedPrice = $this->sanitizePrice(isset($cartItem['price']) ? $cartItem['price'] : 0);
            $quantity = (int)(isset($cartItem['quantity']) ? $cartItem['quantity'] : 0);
            $cartTotal += $sanitizedPrice * $quantity;
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
        $rowId = $request->input('id');  // This is now row_id, not product_id
        $ice = $request->input('ice', 'normal');
        $sugar = $request->input('sugar', 'normal');

        // Get cart from session using standardized 'cart' key
        $cart = session()->get('cart', []);
        
        // Check if item exists by row_id
        if (!isset($cart[$rowId])) {
            return response()->json(['success' => false]);
        }

        // Update the item's customization
        $notes = "Es: " . ($ice === 'less' ? 'Sedikit' : ($ice === 'no' ? 'Tidak Ada' : 'Normal')) . 
                ", Gula: " . ($sugar === 'less' ? 'Sedikit' : ($sugar === 'no' ? 'Tanpa Gula' : 'Normal'));
        
        $cart[$rowId]['ice'] = $ice;
        $cart[$rowId]['sugar'] = $sugar;
        $cart[$rowId]['notes'] = $notes;
        
        // Save back to session using standardized 'cart' key
        session()->put('cart', $cart);

        // Calculate new cart total with sanitized prices
        $cartTotal = 0;
        foreach ($cart as $cartItem) {
            $sanitizedPrice = $this->sanitizePrice(isset($cartItem['price']) ? $cartItem['price'] : 0);
            $quantity = (int)(isset($cartItem['quantity']) ? $cartItem['quantity'] : 0);
            $cartTotal += $sanitizedPrice * $quantity;
        }

        return response()->json([
            'success' => true,
            'notes' => $notes,
            'cart_total' => $cartTotal
        ]);
    }

    public function remove(Request $request)
    {
        $rowId = $request->input('id');  // This is now row_id, not product_id

        // Get cart from session using standardized 'cart' key
        $cart = session()->get('cart', []);
        
        // Remove item if exists by row_id
        if (isset($cart[$rowId])) {
            unset($cart[$rowId]);
            
            // Save back to session using standardized 'cart' key
            session()->put('cart', $cart);

            // Calculate new cart total with sanitized prices
            $cartTotal = 0;
            foreach ($cart as $cartItem) {
                $sanitizedPrice = $this->sanitizePrice(isset($cartItem['price']) ? $cartItem['price'] : 0);
                $quantity = (int)(isset($cartItem['quantity']) ? $cartItem['quantity'] : 0);
                $cartTotal += $sanitizedPrice * $quantity;
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
