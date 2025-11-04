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
        // Expect cart items passed as JSON in query or session
        return view('checkout');
    }

    // Store order and show payment
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'telepon_pelanggan' => 'nullable|string|max:50',
            'tipe_pengambilan' => 'required|in:dine-in,takeaway,delivery',
            'items' => 'required|json',
            'total_harga' => 'required|numeric',
        ]);

        $order = Order::create([
            'order_number' => strtoupper(Str::random(8)),
            'nama_pelanggan' => $data['nama_pelanggan'],
            'telepon_pelanggan' => $data['telepon_pelanggan'] ?? null,
            'tipe_pengambilan' => $data['tipe_pengambilan'],
            'total_harga' => $data['total_harga'],
            'items' => json_decode($data['items'], true),
            'status' => 'Pending',
        ]);

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
}
