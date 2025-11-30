@extends('layouts.admin')

@section('title', 'Daftar Pesanan (Admin)')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Daftar Pesanan</h2>
        <div>
            <a href="{{ route('admin.menu.index') }}" class="bg-gray-200 px-3 py-2 rounded">Lihat Menu</a>
        </div>
    </div>

    <table class="w-full table-auto">
        <thead>
            <tr class="text-left">
                <th class="px-2 py-1">#</th>
                <th class="px-2 py-1">Order #</th>
                <th class="px-2 py-1">Nama</th>
                <th class="px-2 py-1">Telepon</th>
                <th class="px-2 py-1">Tipe</th>
                <th class="px-2 py-1">Pembayaran</th>
                <th class="px-2 py-1">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-t">
                    <td class="px-2 py-2">{{ $order->id }}</td>
                    <td class="px-2 py-2">{{ $order->order_number }}</td>
                    <td class="px-2 py-2">{{ $order->nama_pelanggan }}</td>
                    <td class="px-2 py-2">{{ $order->telepon_pelanggan ?? '-' }}</td>
                    <td class="px-2 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $order->tipe_pengambilan == 'dine-in' ? 'bg-blue-100 text-blue-800' : 
                               ($order->tipe_pengambilan == 'takeaway' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($order->tipe_pengambilan) }}
                        </span>
                    </td>
                    <td class="px-2 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold uppercase
                            {{ $order->payment_method == 'qris' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $order->payment_method }}
                        </span>
                    </td>
                    <td class="px-2 py-2">Rp {{ number_format($order->total_harga,0,',','.') }}</td>
                    <td class="px-2 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold 
                            {{ $order->status == 'Completed' ? 'bg-green-100 text-green-800' : 
                               ($order->status == 'Processing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $order->status }}
                        </span>
                        @if($order->status == 'Processing')
                            <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST" class="inline-block ml-2">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs" onclick="return confirm('Selesaikan pesanan ini?')">
                                    Selesaikan
                                </button>
                            </form>
                        @elseif($order->status == 'Pending')
                            <form action="{{ route('admin.orders.confirmPayment', $order->id) }}" method="POST" class="inline-block ml-2">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" onclick="return confirm('Konfirmasi pembayaran pesanan ini?')">
                                    Konfirmasi Bayar
                                </button>
                            </form>
                        @endif
                    </td>
                    <td class="px-2 py-2">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-2 py-2">
                        <a href="{{ route('orders.receipt', ['order' => $order->id]) }}" class="text-blue-600">Lihat Struk</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
