@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="flex items-center gap-3 mb-8">
    @svg('heroicon-o-chart-bar-square', 'w-7 h-7 text-[#5C4A35]')
    <div>
        <h2 class="text-2xl font-bold text-[#3E2F1E]">Dashboard Admin</h2>
        <p class="text-[#9e8065] text-sm">Selamat datang, {{ auth()->user()->name }}</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('products.index') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 hover:shadow-md hover:border-[#c4a882] transition group">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-[#f5e6d3] rounded-xl p-3">
                @svg('heroicon-o-shopping-bag', 'w-6 h-6 text-[#5C4A35]')
            </div>
            @svg('heroicon-o-arrow-top-right-on-square', 'w-4 h-4 text-[#c4a882] group-hover:text-[#5C4A35] transition')
        </div>
        <p class="text-[#9e8065] text-sm">Produk</p>
        <p class="font-bold text-2xl text-[#3E2F1E]">{{ $totalProducts }}</p>
    </a>
    <a href="{{ route('categories.index') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 hover:shadow-md hover:border-[#c4a882] transition group">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-[#f5e6d3] rounded-xl p-3">
                @svg('heroicon-o-folder', 'w-6 h-6 text-[#5C4A35]')
            </div>
            @svg('heroicon-o-arrow-top-right-on-square', 'w-4 h-4 text-[#c4a882] group-hover:text-[#5C4A35] transition')
        </div>
        <p class="text-[#9e8065] text-sm">Kategori</p>
        <p class="font-bold text-2xl text-[#3E2F1E]">{{ $totalCategories }}</p>
    </a>
    <a href="{{ route('admin.orders') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 hover:shadow-md hover:border-[#c4a882] transition group">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-[#f5e6d3] rounded-xl p-3">
                @svg('heroicon-o-bell', 'w-6 h-6 text-[#5C4A35]')
            </div>
            @svg('heroicon-o-arrow-top-right-on-square', 'w-4 h-4 text-[#c4a882] group-hover:text-[#5C4A35] transition')
        </div>
        <p class="text-[#9e8065] text-sm">Pesanan</p>
        <p class="font-bold text-2xl text-[#3E2F1E]">{{ $totalOrders }}</p>
    </a>
    <a href="{{ route('admin.reports') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 hover:shadow-md hover:border-[#c4a882] transition group">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-[#f5e6d3] rounded-xl p-3">
                @svg('heroicon-o-banknotes', 'w-6 h-6 text-[#5C4A35]')
            </div>
            @svg('heroicon-o-arrow-top-right-on-square', 'w-4 h-4 text-[#c4a882] group-hover:text-[#5C4A35] transition')
        </div>
        <p class="text-[#9e8065] text-sm">Pendapatan</p>
        <p class="font-bold text-lg text-[#5C4A35]">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </a>
</div>

{{-- Menu Cards --}}
<h3 class="text-sm font-semibold text-[#9e8065] uppercase tracking-wider mb-4">Menu Pengelolaan</h3>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <a href="{{ route('users.index') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6 hover:shadow-md hover:border-[#c4a882] transition flex items-center gap-4 group">
        <div class="bg-[#f0e0cc] rounded-xl p-4">
            @svg('heroicon-o-user-group', 'w-7 h-7 text-[#5C4A35]')
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-[#3E2F1E]">Kelola User</h3>
            <p class="text-[#9e8065] text-sm hidden lg:block">Tambah admin / kasir</p>
        </div>
        @svg('heroicon-o-chevron-right', 'w-5 h-5 text-[#c4a882] group-hover:text-[#5C4A35] transition')
    </a>
    <a href="{{ route('admin.reports') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6 hover:shadow-md hover:border-[#c4a882] transition flex items-center gap-4 group">
        <div class="bg-[#f0e0cc] rounded-xl p-4">
            @svg('heroicon-o-document-text', 'w-7 h-7 text-[#5C4A35]')
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-[#3E2F1E]">Laporan Penjualan</h3>
            <p class="text-[#9e8065] text-sm hidden lg:block">Lihat rekap transaksi</p>
        </div>
        @svg('heroicon-o-chevron-right', 'w-5 h-5 text-[#c4a882] group-hover:text-[#5C4A35] transition')
    </a>
    <a href="{{ route('admin.analytics') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6 hover:shadow-md hover:border-[#c4a882] transition flex items-center gap-4 group">
        <div class="bg-[#f0e0cc] rounded-xl p-4">
            @svg('heroicon-o-chart-pie', 'w-7 h-7 text-[#5C4A35]')
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-[#3E2F1E]">Analytics</h3>
            <p class="text-[#9e8065] text-sm hidden lg:block">Dashboard performa</p>
        </div>
        @svg('heroicon-o-chevron-right', 'w-5 h-5 text-[#c4a882] group-hover:text-[#5C4A35] transition')
    </a>
    <a href="{{ route('admin.settings') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6 hover:shadow-md hover:border-[#c4a882] transition flex items-center gap-4 group">
        <div class="bg-[#f0e0cc] rounded-xl p-4">
            @svg('heroicon-o-cog-8-tooth', 'w-7 h-7 text-[#5C4A35]')
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-[#3E2F1E]">Pengaturan</h3>
            <p class="text-[#9e8065] text-sm hidden lg:block">Informasi warung</p>
        </div>
        @svg('heroicon-o-chevron-right', 'w-5 h-5 text-[#c4a882] group-hover:text-[#5C4A35] transition')
    </a>
</div>

@endsection