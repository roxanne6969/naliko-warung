@extends('layouts.app')

@section('title', 'Monitoring Stok - Kasir')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-archive-box', 'w-6 h-6 text-[#5C4A35]')
        <div>
            <h2 class="text-2xl font-bold text-[#3E2F1E]">Stok Produk</h2>
            <p class="text-[#9e8065] text-sm">Monitoring ketersediaan produk</p>
        </div>
        <span class="flex items-center gap-1 bg-[#f5e6d3] text-[#5C4A35] text-xs px-2.5 py-1 rounded-full font-semibold border border-[#e8d5c1]" title="Anda hanya dapat melihat data stok.">
            @svg('heroicon-o-eye', 'w-3.5 h-3.5') Mode Lihat Saja
        </span>
    </div>
    <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-1 text-[#9e8065] hover:text-[#5C4A35] text-sm transition">
        @svg('heroicon-o-arrow-left', 'w-4 h-4') Kembali
    </a>
</div>

{{-- Filter & Search --}}
<div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-4 mb-6">
    <form action="{{ route('kasir.stok') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
        <div class="md:col-span-2 relative">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                @svg('heroicon-o-magnifying-glass', 'w-4 h-4 text-[#9e8065]')
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari produk..."
                class="w-full border border-[#e8d5c1] rounded-xl pl-9 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
        </div>
        <div class="relative">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                @svg('heroicon-o-tag', 'w-4 h-4 text-[#9e8065]')
            </div>
            <select name="category" class="w-full border border-[#e8d5c1] rounded-xl pl-9 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E] appearance-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 flex items-center justify-center gap-2 bg-[#5C4A35] text-[#F7E6CC] px-4 py-2.5 rounded-xl hover:bg-[#3E2F1E] transition text-sm font-semibold">
                @svg('heroicon-o-funnel', 'w-4 h-4') Filter
            </button>
            <a href="{{ route('kasir.stok') }}" class="flex items-center justify-center gap-1 bg-[#f5e6d3] text-[#5C4A35] px-4 py-2.5 rounded-xl hover:bg-[#e8d5c1] transition text-sm font-semibold">
                @svg('heroicon-o-x-mark', 'w-4 h-4') Reset
            </a>
        </div>
    </form>
</div>

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
            @forelse($products as $product)
            <tr class="hover:bg-[#fdf5ec] transition">
                <td class="px-4 py-3">
                    <p class="font-semibold text-[#3E2F1E]">{{ $product->name }}</p>
                    <p class="text-[#9e8065] text-xs">{{ Str::limit($product->description, 40) }}</p>
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2.5 py-1 bg-[#f5e6d3] text-[#5C4A35] rounded-full font-semibold">
                        {{ $product->category->name }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm font-semibold text-[#5C4A35]">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3">
                    <span class="text-sm font-bold {{ $product->stock < 5 ? 'text-red-500' : 'text-[#3E2F1E]' }}">
                        {{ $product->stock }}
                    </span>
                    @if($product->stock < 5 && $product->stock > 0)
                        <span class="ml-1 text-xs text-red-400">(Hampir habis)</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $product->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                        {{ $product->is_available ? 'Tersedia' : 'Habis' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <button disabled title="Hanya Admin yang dapat mengubah data"
                            class="flex items-center gap-1 text-xs text-[#9e8065] cursor-not-allowed bg-[#f5e6d3] px-2.5 py-1.5 rounded-lg opacity-60">
                            @svg('heroicon-o-pencil-square', 'w-3.5 h-3.5') Edit
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-[#9e8065]">
                    <div class="w-12 h-12 bg-[#f5e6d3] rounded-full flex items-center justify-center mx-auto mb-2">
                        @svg('heroicon-o-archive-box', 'w-6 h-6 text-[#5C4A35]')
                    </div>
                    <p>Tidak ada produk ditemukan.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
