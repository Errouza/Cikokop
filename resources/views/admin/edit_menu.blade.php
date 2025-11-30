@extends('layouts.admin')

@section('title', 'Edit Menu (Admin)')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Menu</h2>



    <form method="post" action="{{ route('admin.menu.update', ['menu' => $menu->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="block mb-1">Nama</label>
            <input name="nama" class="w-full border px-3 py-2 rounded" value="{{ old('nama', $menu->nama) }}" required>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Deskripsi</label>
            <textarea name="deskripsi" class="w-full border px-3 py-2 rounded">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Harga</label>
            <input name="harga" type="number" step="0.01" class="w-full border px-3 py-2 rounded" value="{{ old('harga', $menu->harga) }}" required>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Kategori</label>
            <input name="kategori" class="w-full border px-3 py-2 rounded" value="{{ old('kategori', $menu->kategori) }}">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Gambar Saat Ini</label>
            @if($menu->gambar_url)
                <div class="mb-2"><img src="{{ asset($menu->gambar_url) }}" alt="{{ $menu->nama }}" class="w-32 h-24 object-cover rounded"></div>
                <label class="inline-flex items-center"><input type="checkbox" name="remove_image" value="1" class="mr-2"> Hapus gambar saat ini</label>
            @else
                <div class="text-gray-400">Tidak ada gambar</div>
            @endif
        </div>

        <div class="mb-3">
            <label class="block mb-1">Upload Gambar Baru (opsional)</label>
            <input name="gambar" type="file" accept="image/*" class="w-full border px-3 py-2 rounded">
            <div class="mt-2">
                <label class="block mb-1">Atau: Gambar URL (opsional)</label>
                <input name="gambar_url" class="w-full border px-3 py-2 rounded" value="{{ old('gambar_url', $menu->gambar_url) }}">
            </div>
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.menu.index') }}" class="bg-gray-200 px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection
