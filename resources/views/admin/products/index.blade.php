@extends('layouts.app')

@section('title', 'Produk - Admin')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-shopping-bag', 'w-6 h-6 text-[#5C4A35]')
        <h2 class="text-2xl font-bold text-[#3E2F1E]">Kelola Produk</h2>
    </div>
    <a href="{{ route('products.create') }}"
        class="flex items-center gap-2 bg-[#5C4A35] text-[#F7E6CC] px-4 py-2 rounded-lg hover:bg-[#3E2F1E] transition text-sm font-semibold">
        @svg('heroicon-o-plus', 'w-4 h-4')
        Tambah Produk
    </a>
</div>

@if(session('success'))
    <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">
        @svg('heroicon-o-check-circle', 'w-5 h-5') {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] overflow-hidden">
    <table class="w-full">
        <thead class="bg-[#f5e6d3]">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Produk</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Kategori</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Harga</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Stok</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Status</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e0cc]">
            @foreach($products as $product)
            <tr class="hover:bg-[#fdf5ec] transition">
                <td class="px-4 py-3">
                    <p class="font-semibold text-[#3E2F1E]">{{ $product->name }}</p>
                    <p class="text-[#9e8065] text-xs">{{ Str::limit($product->description, 40) }}</p>
                </td>
                <td class="px-4 py-3 text-sm text-[#5C4A35]">{{ $product->category->name }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-[#5C4A35]">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-sm {{ $product->stock < 5 ? 'text-red-500 font-semibold' : 'text-[#5C4A35]' }}">
                    {{ $product->stock }}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $product->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                        {{ $product->is_available ? 'Tersedia' : 'Habis' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('products.edit', $product) }}"
                            class="flex items-center gap-1 text-xs bg-[#f5e6d3] text-[#5C4A35] px-3 py-1.5 rounded-lg hover:bg-[#e8d5c1] transition">
                            @svg('heroicon-o-pencil-square', 'w-3.5 h-3.5') Edit
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus produk ini?')"
                                class="flex items-center gap-1 text-xs bg-red-50 text-red-500 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                                @svg('heroicon-o-trash', 'w-3.5 h-3.5') Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection