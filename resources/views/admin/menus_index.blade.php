@extends('layouts.admin')

@section('title', 'Daftar Menu (Admin)')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Daftar Menu</h2>
        <div>
            <a href="{{ route('admin.menu.create') }}" class="bg-blue-600 text-white px-3 py-2 rounded">Tambah Menu</a>
            <a href="{{ route('admin.orders.index') }}" class="ml-2 bg-gray-200 px-3 py-2 rounded">Lihat Pesanan</a>
        </div>
    </div>

    <table class="w-full table-auto">
        <thead>
            <tr class="text-left">
                <th class="px-2 py-1">#</th>
                <th class="px-2 py-1">Gambar</th>
                <th class="px-2 py-1">Nama</th>
                <th class="px-2 py-1">Kategori</th>
                <th class="px-2 py-1">Harga</th>
                <th class="px-2 py-1">Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
                <tr class="border-t">
                    <td class="px-2 py-2">{{ $menu->id }}</td>
                    <td class="px-2 py-2">
                        @if($menu->gambar_url)
                            <img src="{{ asset($menu->gambar_url) }}" alt="{{ $menu->nama }}" class="w-16 h-12 object-cover rounded" />
                        @else
                            <div class="text-gray-400">-</div>
                        @endif
                    </td>
                    <td class="px-2 py-2">{{ $menu->nama }}</td>
                    <td class="px-2 py-2">{{ $menu->kategori ?? '-' }}</td>
                    <td class="px-2 py-2">Rp {{ number_format($menu->harga,0,',','.') }}</td>
                    <td class="px-2 py-2">{{ $menu->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-2 py-2">
                        <a href="{{ route('admin.menu.edit', ['menu' => $menu->id]) }}" class="text-blue-600 mr-2">Edit</a>
                        <form action="{{ route('admin.menu.destroy', ['menu' => $menu->id]) }}" method="post" style="display:inline-block;" onsubmit="return confirm('Hapus menu ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
