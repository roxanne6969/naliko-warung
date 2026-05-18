@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">➕ Tambah Produk</h2>
    <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-orange-500 text-sm">← Kembali</a>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="text-sm text-gray-600 mb-1 block">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm text-gray-600 mb-1 block">Kategori</label>
            <select name="category_id" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price') }}"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
        </div>

        <div>
            <label class="text-sm text-gray-600 mb-1 block">Deskripsi</label>
            <textarea name="description" rows="3"
                class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="text-sm text-gray-600 mb-1 block">Foto Produk</label>
            <input type="file" name="image" accept="image/*"
                class="w-full border rounded-lg px-4 py-2">
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_available" id="is_available" value="1" checked>
            <label for="is_available" class="text-sm text-gray-600">Produk tersedia</label>
        </div>

        <button class="w-full bg-orange-500 text-white py-3 rounded-xl hover:bg-orange-600 font-semibold">
            Simpan Produk
        </button>
    </form>
</div>

@endsection