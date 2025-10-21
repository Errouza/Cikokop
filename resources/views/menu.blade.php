@extends('layouts.app')

@section('title', 'Menu')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Menu</h2>
    <div>
        <a href="{{ route('checkout') }}" class="bg-blue-600 text-white px-3 py-2 rounded">Keranjang (<span id="cart-count">0</span>) - Total: Rp <span id="cart-total">0</span></a>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($menus as $menu)
        <div class="bg-white rounded shadow p-4 flex flex-col">
            <div class="h-40 bg-gray-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                @if($menu->gambar_url)
                    <img src="{{ $menu->gambar_url }}" alt="{{ $menu->nama }}" class="object-cover w-full h-full">
                @else
                    <div class="text-gray-400">No Image</div>
                @endif
            </div>
            <h3 class="font-semibold text-lg">{{ $menu->nama }}</h3>
            <p class="text-sm text-gray-600 flex-1">{{ $menu->deskripsi }}</p>
            <div class="mt-3 flex items-center justify-between">
                <div class="text-green-700 font-bold">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                <button class="add-to-cart bg-yellow-500 text-white px-3 py-1 rounded" data-id="{{ $menu->id }}" data-name="{{ $menu->nama }}" data-price="{{ $menu->harga }}">Tambah ke Keranjang</button>
            </div>
        </div>
    @endforeach
</div>

@endsection
