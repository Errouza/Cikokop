@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<h2 class="text-2xl font-bold mb-4">Pembayaran - Order #{{ $order->order_number }}</h2>

<div class="bg-white rounded shadow p-4 mb-4">
    <div class="flex items-center space-x-4">
        <div class="w-48 h-48 bg-gray-100 flex items-center justify-center">
            <!-- Placeholder QR image -->
            <img src="{{ asset('image/QrisGWEH.svg') }}" alt="QRIS placeholder" class="object-cover w-full h-full">
        </div>
        <div>
            <div>Nama: <strong>{{ $order->nama_pelanggan }}</strong></div>
            <div>Total: <strong>Rp {{ number_format($order->total_harga,0,',','.') }}</strong></div>
            <div>Status: <strong>{{ $order->status }}</strong></div>
        </div>
    </div>

    <form method="post" action="{{ route('payment.confirm', ['order' => $order->id]) }}" class="mt-4">
        @csrf
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Konfirmasi Pembayaran</button>
    </form>

    @if($order->status !== 'Pending')
        <div class="mt-3">
            <a href="{{ route('orders.receipt', ['order' => $order->id]) }}" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded">Print Struk</a>
        </div>
    @endif
</div>

@if(session('success'))
    <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif

@endsection
