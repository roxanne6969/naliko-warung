@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-plus-circle', 'w-6 h-6 text-[#5C4A35]')
        <h2 class="text-2xl font-bold text-[#3E2F1E]">Tambah Produk</h2>
    </div>
    <a href="{{ route('products.index') }}"
        class="flex items-center gap-1 text-[#9e8065] hover:text-[#5C4A35] text-sm transition">
        @svg('heroicon-o-arrow-left', 'w-4 h-4') Kembali
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6 max-w-2xl">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="text-sm font-semibold text-[#5C4A35] mb-1 block">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-semibold text-[#5C4A35] mb-1 block">Kategori</label>
            <select name="category_id" class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-[#5C4A35] mb-1 block">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price') }}"
                    class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
            </div>
            <div>
                <label class="text-sm font-semibold text-[#5C4A35] mb-1 block">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}"
                    class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
            </div>
        </div>

        <div>
            <label class="text-sm font-semibold text-[#5C4A35] mb-1 block">Deskripsi</label>
            <textarea name="description" rows="3"
                class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E] resize-none">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="text-sm font-semibold text-[#5C4A35] mb-1 block">Foto Produk</label>
            <input type="file" name="image" accept="image/*"
                class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 bg-[#fdfaf7] text-[#5C4A35] text-sm">
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_available" id="is_available" value="1" checked
                class="w-4 h-4 accent-[#5C4A35]">
            <label for="is_available" class="text-sm text-[#5C4A35]">Produk tersedia</label>
        </div>

        <button class="w-full flex items-center justify-center gap-2 bg-[#5C4A35] text-[#F7E6CC] py-3 rounded-xl hover:bg-[#3E2F1E] font-semibold transition">
            @svg('heroicon-o-check', 'w-5 h-5') Simpan Produk
        </button>
    </form>
</div>

@endsection