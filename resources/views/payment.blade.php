@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<h2 class="text-2xl font-bold mb-4">Pembayaran - Order #{{ $order->order_number }}</h2>

<div class="bg-white rounded shadow p-4 mb-4">
    <div class="flex flex-col md:flex-row items-start md:space-x-8 space-y-6 md:space-y-0">
        @if($order->payment_method == 'qris')
            <div class="w-full md:w-auto flex justify-center">
                <div class="bg-white p-4 rounded-xl shadow-lg border border-gray-100">
                    <div class="text-center mb-3 font-bold text-gray-700">Scan QRIS untuk Membayar</div>
                    <div class="w-64 h-64 bg-gray-50 flex items-center justify-center rounded-lg overflow-hidden border-2 border-dashed border-gray-200 cursor-zoom-in" onclick="openQrisModal()">
                        <!-- QR image -->
                        <img src="{{ asset('image/QrisGWEH.svg') }}" alt="QRIS Code" class="w-full h-full object-contain">
                    </div>
                    <div class="text-center mt-3 text-sm text-gray-500">NMID: ID102003004005</div>
                    <div class="text-center mt-1 text-xs text-blue-500">(Klik gambar untuk memperbesar)</div>
                </div>
            </div>
        @else
            <div class="w-full md:w-64 h-64 bg-yellow-50 flex items-center justify-center flex-col p-6 text-center rounded-xl border-2 border-yellow-200">
                <div class="text-6xl mb-4">💵</div>
                <div class="font-bold text-xl text-gray-700">Cash / Tunai</div>
                <div class="text-sm text-gray-500 mt-2">Bayar di Kasir</div>
            </div>
        @endif
        
        <div class="flex-1 w-full">
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Rincian Pesanan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Pelanggan</span>
                        <span class="font-semibold text-gray-900">{{ $order->nama_pelanggan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Order</span>
                        <span class="font-mono font-medium text-gray-900">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="font-semibold uppercase text-gray-900">{{ $order->payment_method }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-gray-200 mt-2">
                        <span class="text-gray-800 font-bold">Total Tagihan</span>
                        <span class="text-2xl font-bold text-dca259">Rp {{ number_format($order->total_harga,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-gray-600">Status Pembayaran</span>
                        <span class="px-3 py-1 rounded-full text-sm font-bold 
                            {{ $order->status == 'Completed' ? 'bg-green-100 text-green-700' : 
                               ($order->status == 'Processing' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($order->payment_method == 'qris' && $order->status == 'Pending')
        <form method="post" action="{{ route('payment.confirm', ['order' => $order->id]) }}" class="mt-4">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Konfirmasi Pembayaran</button>
        </form>
    @elseif($order->payment_method == 'cash' && $order->status == 'Pending')
        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded text-yellow-800">
            <p class="font-semibold">Silakan lakukan pembayaran di kasir.</p>
            <p class="text-sm">Status pesanan akan diperbarui setelah pembayaran dikonfirmasi oleh petugas.</p>
        </div>
    @endif

    @if($order->status !== 'Pending')
        <div class="mt-3">
            <a href="{{ route('orders.receipt', ['order' => $order->id]) }}" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded">Print Struk</a>
        </div>
    @endif
</div>

@if(session('success'))
    <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif

<!-- QRIS Modal -->
<div id="qris-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-75 backdrop-blur-sm transition-opacity duration-300" onclick="closeQrisModal()">
    <div class="relative bg-white p-4 rounded-2xl shadow-2xl max-w-lg w-full mx-4 transform transition-all scale-95 opacity-0" id="qris-modal-content" onclick="event.stopPropagation()">
        <button onclick="closeQrisModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="text-center mb-4 font-bold text-xl text-gray-800">Scan QRIS</div>
        <div class="flex justify-center">
            <img src="{{ asset('image/QrisGWEH.svg') }}" alt="QRIS Code Large" class="w-full max-h-[70vh] object-contain rounded-lg">
        </div>
        <div class="text-center mt-4 text-gray-600 font-medium">NMID: ID102003004005</div>
    </div>
</div>

<script>
    function openQrisModal() {
        const modal = document.getElementById('qris-modal');
        const content = document.getElementById('qris-modal-content');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Small delay to allow display:flex to apply before animating opacity
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeQrisModal() {
        const modal = document.getElementById('qris-modal');
        const content = document.getElementById('qris-modal-content');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQrisModal();
        }
    });
</script>

@endsection
