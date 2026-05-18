@extends('layouts.app')

@section('title', 'Kategori - Admin')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">📁 Kelola Kategori</h2>
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-orange-500 text-sm">← Kembali</a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Form Tambah --}}
    <div class="bg-[#F5E2C7] rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-800 mb-4">Tambah Kategori</h3>
        <form action="{{ route('categories.store') }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="name" placeholder="Nama kategori"
                class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                Tambah
            </button>
        </form>
    </div>

    {{-- List Kategori --}}
    <div class="bg-[#F5E2C7] rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-800 mb-4">Daftar Kategori</h3>
        <div class="space-y-2">
            @foreach($categories as $category)
            <div class="flex items-center justify-between py-2 border-b last:border-0">
                <div>
                    <p class="font-semibold text-gray-800">{{ $category->name }}</p>
                    <p class="text-gray-400 text-xs">{{ $category->products_count }} produk</p>
                </div>
                <form action="{{ route('categories.destroy', $category) }}" method="POST">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Hapus kategori ini?')"
                        class="text-red-400 hover:text-red-600 text-sm">Hapus</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection