@extends('layouts.admin')

@section('title', 'Tambah Menu (Admin)')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Tambah Menu (Admin)</h2>



    <form method="post" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="block mb-1">Nama</label>
            <input name="nama" class="w-full border px-3 py-2 rounded" value="{{ old('nama') }}" required>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Deskripsi</label>
            <textarea name="deskripsi" class="w-full border px-3 py-2 rounded">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Harga</label>
            <input name="harga" type="number" step="0.01" class="w-full border px-3 py-2 rounded" value="{{ old('harga') }}" required>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Kategori</label>
            <input name="kategori" class="w-full border px-3 py-2 rounded" value="{{ old('kategori') }}">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Upload Gambar (opsional)</label>
            <input name="gambar" type="file" accept="image/*" class="w-full border px-3 py-2 rounded">
            <div class="mt-2">
                <label class="block mb-1">Atau: Gambar URL (opsional)</label>
                <input name="gambar_url" class="w-full border px-3 py-2 rounded" value="{{ old('gambar_url') }}">
            </div>
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.menu.index') }}" class="bg-gray-200 px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>

@endsection
