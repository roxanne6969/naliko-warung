@extends('layouts.app')
@section('title', 'Kategori - Admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-folder', 'w-6 h-6 text-[#5C4A35]')
        <h2 class="text-2xl font-bold text-[#3E2F1E]">Kelola Kategori</h2>
    </div>
    <a href="{{ route('admin.dashboard') }}"
        class="flex items-center gap-1 text-[#9e8065] hover:text-[#5C4A35] text-sm transition">
        @svg('heroicon-o-arrow-left', 'w-4 h-4') Kembali
    </a>
</div>
@if(session('success'))
    <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">
        @svg('heroicon-o-check-circle', 'w-5 h-5') {{ session('success') }}
    </div>
@endif
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6">
        <h3 class="font-bold text-[#3E2F1E] mb-4">Tambah Kategori</h3>
        <form action="{{ route('categories.store') }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="name" placeholder="Nama kategori"
                class="flex-1 border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
            <button class="flex items-center gap-1 bg-[#5C4A35] text-[#F7E6CC] px-4 py-2.5 rounded-xl hover:bg-[#3E2F1E] transition text-sm font-semibold">
                @svg('heroicon-o-plus', 'w-4 h-4') Tambah
            </button>
        </form>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6">
        <h3 class="font-bold text-[#3E2F1E] mb-4">Daftar Kategori</h3>
        <div class="space-y-2">
            @foreach($categories as $category)
            <div class="flex items-center justify-between py-2.5 border-b border-[#f0e0cc] last:border-0">
                <div>
                    <p class="font-semibold text-[#3E2F1E]">{{ $category->name }}</p>
                    <p class="text-[#9e8065] text-xs">{{ $category->products_count }} produk</p>
                </div>
                <form action="{{ route('categories.destroy', $category) }}" method="POST">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Hapus kategori ini?')"
                        class="flex items-center gap-1 text-xs text-red-400 hover:text-red-600 transition">
                        @svg('heroicon-o-trash', 'w-3.5 h-3.5') Hapus
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection