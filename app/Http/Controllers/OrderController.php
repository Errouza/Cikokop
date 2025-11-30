<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // Show menu page
    public function menu()
    {
        $menus = Menu::orderBy('kategori')->get();
        return view('menu', compact('menus'));
    }

    // Show checkout page
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        $total = 0;

        // Calculate total with sanitization
        foreach ($cart as $key => $item) {
            $price = $item['price'] ?? 0;
            $qty = $item['quantity'] ?? 0;
            
            // Sanitize price
            if (is_numeric($price)) {
                $cleanPrice = (float)$price;
            } else {
                $cleanPrice = (float) preg_replace('/[^0-9]/', '', (string)$price);
            }
            
            // Fallback for 0 price if original was numeric
            if ($cleanPrice == 0 && is_numeric($price) && $price > 0) {
                $cleanPrice = (float)$price;
            }

            $total += $cleanPrice * $qty;
            
            // Ensure price is clean in the cart array passed to view
            $cart[$key]['price'] = $cleanPrice;
        }

        return view('checkout', compact('cart', 'total'));
    }

    // Store order and show payment
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'telepon_pelanggan' => 'nullable|string|max:50',
            'tipe_pengambilan' => 'required|in:dine-in,takeaway,delivery',
            'payment_method' => 'required|in:qris,cash',
            'items' => 'required|json',
            'total_harga' => 'required|numeric',
        ]);

        $order = Order::create([
            'order_number' => strtoupper(Str::random(8)),
            'nama_pelanggan' => $data['nama_pelanggan'],
            'telepon_pelanggan' => $data['telepon_pelanggan'] ?? null,
            'tipe_pengambilan' => $data['tipe_pengambilan'],
            'payment_method' => $data['payment_method'],
            'total_harga' => $data['total_harga'],
            'items' => json_decode($data['items'], true),
            'status' => 'Pending',
        ]);

        // Clear cart after successful order
        session()->forget('cart');

        return redirect()->route('payment.show', ['order' => $order->id]);
    }

    // Show payment page with QRIS placeholder
    public function payment($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('payment', compact('order'));
    }

    // Confirm payment and update status
    public function confirmPayment($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status = 'Processing';
        $order->save();
        // Redirect to receipt and trigger auto-print in same tab
        return redirect()->route('orders.receipt', ['order' => $order->id, 'auto' => 1]);
    }

    // Show printable receipt
    public function receipt($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('receipt', compact('order'));
    }

    // Admin: list orders (purchases)
    public function adminIndex()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.orders_index', compact('orders'));
    }

    // Admin: Mark order as completed
    public function complete($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status = 'Completed';
        $order->save();
        return redirect()->route('admin.orders.index')->with('success', 'Order marked as completed!');
    }

    // Admin: Confirm payment (Pending -> Processing)
    public function adminConfirmPayment($orderId)
    {
        $order = Order::findOrFail($orderId);
        if ($order->status == 'Pending') {
            $order->status = 'Processing';
            $order->save();
            return redirect()->route('admin.orders.index')->with('success', 'Pembayaran dikonfirmasi!');
        }
        return redirect()->route('admin.orders.index')->with('error', 'Status pesanan tidak valid untuk konfirmasi.');
    }
}
