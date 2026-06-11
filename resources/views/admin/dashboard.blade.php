@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Dashboard Admin</h2>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('products.index') }}" 
        class="bg-white rounded-2xl shadow p-5 hover:shadow-md transition text-center">
        <div class="text-3xl mb-2">🛍️</div>
        <p class="text-gray-400 text-sm">Produk</p>
        <p class="font-bold text-xl text-gray-800">{{ $totalProducts }}</p>
    </a>
    <a href="{{ route('categories.index') }}" 
        class="bg-white rounded-2xl shadow p-5 hover:shadow-md transition text-center">
        <div class="text-3xl mb-2">📁</div>
        <p class="text-gray-400 text-sm">Kategori</p>
        <p class="font-bold text-xl text-gray-800">{{ $totalCategories }}</p>
    </a>
    <a href="{{ route('admin.orders') }}" 
        class="bg-white rounded-2xl shadow p-5 hover:shadow-md transition text-center">
        <div class="text-3xl mb-2">🔔</div>
        <p class="text-gray-400 text-sm">Pesanan</p>
        <p class="font-bold text-xl text-gray-800">{{ $totalOrders }}</p>
    </a>
    <a href="{{ route('admin.reports') }}" 
        class="bg-white rounded-2xl shadow p-5 hover:shadow-md transition text-center">
        <div class="text-3xl mb-2">💰</div>
        <p class="text-gray-400 text-sm">Pendapatan</p>
        <p class="font-bold text-orange-500 text-lg">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <a href="{{ route('users.index') }}" 
        class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition flex items-center gap-4">
        <div class="bg-blue-100 rounded-xl p-4 text-3xl">👥</div>
        <div>
            <h3 class="font-bold text-gray-800">Kelola User</h3>
            <p class="text-gray-400 text-sm">Tambah & hapus admin/kasir</p>
        </div>
    </a>
    <a href="{{ route('admin.reports') }}" 
        class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition flex items-center gap-4">
        <div class="bg-green-100 rounded-xl p-4 text-3xl">📈</div>
        <div>
            <h3 class="font-bold text-gray-800">Laporan Penjualan</h3>
            <p class="text-gray-400 text-sm">Lihat rekap transaksi</p>
        </div>
    </a>
</div>

@endsection