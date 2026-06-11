@extends('layouts.app')
@section('title', 'Dashboard Kasir')
@section('content')
<div class="flex items-center gap-3 mb-8">
    @svg('heroicon-o-computer-desktop', 'w-7 h-7 text-[#5C4A35]')
    <div>
        <h2 class="text-2xl font-bold text-[#3E2F1E]">Dashboard Kasir</h2>
        <p class="text-[#9e8065] text-sm">Selamat datang, {{ auth()->user()->name }}</p>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center mx-auto mb-2">
            @svg('heroicon-o-credit-card', 'w-5 h-5 text-red-500')
        </div>
        <p class="text-[#9e8065] text-xs">Menunggu Verifikasi</p>
        <p class="font-bold text-2xl text-red-500">{{ $waitingVerification }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
        <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center mx-auto mb-2">
            @svg('heroicon-o-bell', 'w-5 h-5 text-yellow-500')
        </div>
        <p class="text-[#9e8065] text-xs">Pesanan Pending</p>
        <p class="font-bold text-2xl text-yellow-500">{{ $pendingOrders }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-2">
            @svg('heroicon-o-fire', 'w-5 h-5 text-blue-500')
        </div>
        <p class="text-[#9e8065] text-xs">Sedang Diproses</p>
        <p class="font-bold text-2xl text-blue-500">{{ $processingOrders }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
        <div class="w-10 h-10 bg-[#f5e6d3] rounded-xl flex items-center justify-center mx-auto mb-2">
            @svg('heroicon-o-banknotes', 'w-5 h-5 text-[#5C4A35]')
        </div>
        <p class="text-[#9e8065] text-xs">Transaksi Hari Ini</p>
        <p class="font-bold text-lg text-[#5C4A35]">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
    </div>
</div>

<h3 class="text-sm font-semibold text-[#9e8065] uppercase tracking-wider mb-4">Menu Utama</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    <a href="{{ route('kasir.payment.index') }}"
        class="bg-white rounded-2xl shadow-sm border-2 {{ $waitingVerification > 0 ? 'border-red-200 bg-red-50' : 'border-[#e8d5c1]' }} p-6 hover:shadow-md transition flex items-center gap-4 group">
        <div class="relative bg-red-50 rounded-xl p-4 group-hover:bg-red-100 transition">
            @svg('heroicon-o-credit-card', 'w-7 h-7 text-red-500')
            @if($waitingVerification > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">{{ $waitingVerification }}</span>
            @endif
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <h4 class="font-bold text-[#3E2F1E]">Verifikasi Pembayaran</h4>
                @if($waitingVerification > 0)
                    <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full font-semibold">{{ $waitingVerification }} menunggu</span>
                @endif
            </div>
            <p class="text-[#9e8065] text-sm">Cek bukti bayar dari pelanggan</p>
        </div>
        @svg('heroicon-o-chevron-right', 'w-5 h-5 text-[#c4a882] group-hover:text-[#5C4A35] transition')
    </a>
    <a href="{{ route('kasir.orders') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6 hover:shadow-md transition flex items-center gap-4 group">
        <div class="relative bg-yellow-50 rounded-xl p-4 group-hover:bg-yellow-100 transition">
            @svg('heroicon-o-bell', 'w-7 h-7 text-yellow-500')
            @if(($pendingOrders + $processingOrders) > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">{{ $pendingOrders + $processingOrders }}</span>
            @endif
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <h4 class="font-bold text-[#3E2F1E]">Pesanan Online</h4>
                @if(($pendingOrders + $processingOrders) > 0)
                    <span class="bg-yellow-100 text-yellow-600 text-xs px-2 py-0.5 rounded-full">{{ $pendingOrders + $processingOrders }} aktif</span>
                @endif
            </div>
            <p class="text-[#9e8065] text-sm">Terima & proses pesanan yang sudah bayar</p>
        </div>
        @svg('heroicon-o-chevron-right', 'w-5 h-5 text-[#c4a882] group-hover:text-[#5C4A35] transition')
    </a>
    <a href="{{ route('kasir.stok') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6 hover:shadow-md transition flex items-center gap-4 group">
        <div class="bg-blue-50 rounded-xl p-4 group-hover:bg-blue-100 transition">
            @svg('heroicon-o-archive-box', 'w-7 h-7 text-blue-500')
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-[#3E2F1E]">Monitoring Stok</h4>
            <p class="text-[#9e8065] text-sm">Lihat stok produk dan kategori</p>
        </div>
        @svg('heroicon-o-chevron-right', 'w-5 h-5 text-[#c4a882] group-hover:text-[#5C4A35] transition')
    </a>
    <a href="{{ route('kasir.transaction') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6 hover:shadow-md transition flex items-center gap-4 group">
        <div class="bg-[#f5e6d3] rounded-xl p-4 group-hover:bg-[#e8d5c1] transition">
            @svg('heroicon-o-document-text', 'w-7 h-7 text-[#5C4A35]')
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-[#3E2F1E]">Pesanan Offline</h4>
            <p class="text-[#9e8065] text-sm">Transaksi langsung di kasir</p>
        </div>
        @svg('heroicon-o-chevron-right', 'w-5 h-5 text-[#c4a882] group-hover:text-[#5C4A35] transition')
    </a>
    <a href="{{ route('kasir.history') }}"
        class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6 hover:shadow-md transition flex items-center gap-4 group">
        <div class="bg-green-50 rounded-xl p-4 group-hover:bg-green-100 transition">
            @svg('heroicon-o-clipboard-document-list', 'w-7 h-7 text-green-600')
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-[#3E2F1E]">Riwayat Pesanan</h4>
            <p class="text-[#9e8065] text-sm">Lihat daftar transaksi</p>
        </div>
        @svg('heroicon-o-chevron-right', 'w-5 h-5 text-[#c4a882] group-hover:text-[#5C4A35] transition')
    </a>
</div>

@if($activeOrders->count() > 0)
<div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-2">
            @svg('heroicon-o-fire', 'w-5 h-5 text-[#5C4A35]')
            <h3 class="font-bold text-[#3E2F1E]">Pesanan Aktif</h3>
        </div>
        <a href="{{ route('kasir.orders') }}" class="text-[#5C4A35] text-sm hover:underline flex items-center gap-1">
            Lihat semua @svg('heroicon-o-arrow-right', 'w-4 h-4')
        </a>
    </div>
    <div class="space-y-3">
        @foreach($activeOrders as $order)
        <div class="flex items-center justify-between py-2.5 border-b border-[#f0e0cc] last:border-0">
            <div>
                <p class="font-semibold text-[#3E2F1E]">{{ $order->customer_name }}</p>
                <p class="text-[#9e8065] text-xs">{{ $order->table_number ?? 'Tanpa meja' }} · {{ $order->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[#5C4A35] font-semibold text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                <span class="text-xs px-2 py-1 rounded-full font-semibold
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-700
                    @else bg-green-100 text-green-700 @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection