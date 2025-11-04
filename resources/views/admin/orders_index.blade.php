@extends('layouts.app')

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
                <th class="px-2 py-1">Total</th>
                <th class="px-2 py-1">Status</th>
                <th class="px-2 py-1">Tanggal</th>
                <th class="px-2 py-1">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-t">
                    <td class="px-2 py-2">{{ $order->id }}</td>
                    <td class="px-2 py-2">{{ $order->order_number }}</td>
                    <td class="px-2 py-2">{{ $order->nama_pelanggan }}</td>
                    <td class="px-2 py-2">{{ $order->telepon_pelanggan ?? '-' }}</td>
                    <td class="px-2 py-2">Rp {{ number_format($order->total_harga,0,',','.') }}</td>
                    <td class="px-2 py-2">{{ $order->status }}</td>
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
