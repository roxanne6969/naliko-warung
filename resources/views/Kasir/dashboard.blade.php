@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">👋 Dashboard Kasir</h2>
    <p class="text-gray-400 text-sm mt-1">Selamat datang, {{ auth()->user()->name }}</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow p-5 text-center">
        <div class="text-3xl mb-2">💳</div>
        <p class="text-gray-400 text-sm">Menunggu Verifikasi</p>
        <p class="font-bold text-2xl text-red-500">{{ $waitingVerification }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 text-center">
        <div class="text-3xl mb-2">🔔</div>
        <p class="text-gray-400 text-sm">Pesanan Pending</p>
        <p class="font-bold text-2xl text-yellow-500">{{ $pendingOrders }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 text-center">
        <div class="text-3xl mb-2">👨‍🍳</div>
        <p class="text-gray-400 text-sm">Sedang Diproses</p>
        <p class="font-bold text-2xl text-blue-500">{{ $processingOrders }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 text-center">
        <div class="text-3xl mb-2">💰</div>
        <p class="text-gray-400 text-sm">Transaksi Hari Ini</p>
        <p class="font-bold text-lg text-orange-500">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
    </div>
</div>

{{-- Menu Utama Kasir --}}
<h3 class="text-lg font-bold text-gray-700 mb-4">Menu Utama</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

    {{-- Menu 1: Verifikasi Pembayaran --}}
    <a href="{{ route('kasir.payment.index') }}"
        class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition flex items-center gap-4 group border-2 {{ $waitingVerification > 0 ? 'border-red-200 bg-red-50' : 'border-transparent' }}">
        <div class="bg-red-100 rounded-xl p-4 text-3xl group-hover:bg-red-200 transition relative">
            💳
            @if($waitingVerification > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center animate-pulse">
                    {{ $waitingVerification }}
                </span>
            @endif
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <h4 class="font-bold text-gray-800">Verifikasi Pembayaran</h4>
                @if($waitingVerification > 0)
                    <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full font-semibold">{{ $waitingVerification }} menunggu</span>
                @endif
            </div>
            <p class="text-gray-400 text-sm">Cek bukti bayar dari pelanggan</p>
        </div>
        <span class="text-gray-300 group-hover:text-orange-400 transition text-xl">→</span>
    </a>

    {{-- Menu 2: Pesanan Online --}}
    <a href="{{ route('kasir.orders') }}"
        class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition flex items-center gap-4 group">
        <div class="bg-yellow-100 rounded-xl p-4 text-3xl group-hover:bg-yellow-200 transition relative">
            🔔
            @if(($pendingOrders + $processingOrders) > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                    {{ $pendingOrders + $processingOrders }}
                </span>
            @endif
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <h4 class="font-bold text-gray-800">Pesanan Online</h4>
                @if(($pendingOrders + $processingOrders) > 0)
                    <span class="bg-yellow-100 text-yellow-600 text-xs px-2 py-0.5 rounded-full">{{ $pendingOrders + $processingOrders }} aktif</span>
                @endif
            </div>
            <p class="text-gray-400 text-sm">Terima & proses pesanan yang sudah bayar</p>
        </div>
        <span class="text-gray-300 group-hover:text-orange-400 transition text-xl">→</span>
    </a>

    {{-- Menu 3: Manajemen Stok --}}
    <a href="{{ route('products.index') }}"
        class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition flex items-center gap-4 group">
        <div class="bg-blue-100 rounded-xl p-4 text-3xl group-hover:bg-blue-200 transition">📦</div>
        <div class="flex-1">
            <h4 class="font-bold text-gray-800">Manajemen Stok</h4>
            <p class="text-gray-400 text-sm">Tambah stok, ubah harga, produk baru</p>
        </div>
        <span class="text-gray-300 group-hover:text-orange-400 transition text-xl">→</span>
    </a>

    {{-- Menu 4: Pesanan Offline --}}
    <a href="{{ route('kasir.transaction') }}"
        class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition flex items-center gap-4 group">
        <div class="bg-orange-100 rounded-xl p-4 text-3xl group-hover:bg-orange-200 transition">🧾</div>
        <div class="flex-1">
            <h4 class="font-bold text-gray-800">Pesanan Offline</h4>
            <p class="text-gray-400 text-sm">Transaksi langsung di kasir</p>
        </div>
        <span class="text-gray-300 group-hover:text-orange-400 transition text-xl">→</span>
    </a>

    {{-- Menu 5: Riwayat Pesanan --}}
    <a href="{{ route('kasir.history') }}"
        class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition flex items-center gap-4 group">
        <div class="bg-green-100 rounded-xl p-4 text-3xl group-hover:bg-green-200 transition">📋</div>
        <div class="flex-1">
            <h4 class="font-bold text-gray-800">Riwayat Pesanan</h4>
            <p class="text-gray-400 text-sm">Lihat daftar transaksi & cetak ulang</p>
        </div>
        <span class="text-gray-300 group-hover:text-orange-400 transition text-xl">→</span>
    </a>

</div>

{{-- Pesanan Masuk --}}
@if($activeOrders->count() > 0)
<div class="bg-white rounded-2xl shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-gray-800">🍽️ Pesanan Aktif</h3>
        <a href="{{ route('kasir.orders') }}" class="text-orange-500 text-sm hover:underline">Lihat semua →</a>
    </div>
    <div class="space-y-3">
        @foreach($activeOrders as $order)
        <div class="flex items-center justify-between py-2 border-b last:border-0">
            <div>
                <p class="font-semibold text-gray-800">{{ $order->customer_name }}</p>
                <p class="text-gray-400 text-xs">{{ $order->table_number ?? 'Tanpa meja' }} • {{ $order->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-orange-500 font-semibold text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                <span class="text-xs px-2 py-1 rounded-full font-semibold
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-600
                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-600
                    @else bg-green-100 text-green-600 @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection