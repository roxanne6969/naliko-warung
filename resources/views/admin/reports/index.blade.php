@extends('layouts.app')
@section('title', 'Laporan - Admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-chart-bar', 'w-6 h-6 text-[#5C4A35]')
        <h2 class="text-2xl font-bold text-[#3E2F1E]">Laporan Penjualan</h2>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 text-[#9e8065] hover:text-[#5C4A35] text-sm transition">
        @svg('heroicon-o-arrow-left', 'w-4 h-4') Kembali
    </a>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
        <div class="w-10 h-10 bg-[#f5e6d3] rounded-xl flex items-center justify-center mx-auto mb-2">
            @svg('heroicon-o-banknotes', 'w-5 h-5 text-[#5C4A35]')
        </div>
        <p class="text-[#9e8065] text-xs">Total Pendapatan</p>
        <p class="font-bold text-[#5C4A35] text-lg mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
        <div class="w-10 h-10 bg-[#f5e6d3] rounded-xl flex items-center justify-center mx-auto mb-2">
            @svg('heroicon-o-receipt-percent', 'w-5 h-5 text-[#5C4A35]')
        </div>
        <p class="text-[#9e8065] text-xs">Total Transaksi</p>
        <p class="font-bold text-[#3E2F1E] text-2xl mt-1">{{ $totalTransactions }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
        <div class="w-10 h-10 bg-[#f5e6d3] rounded-xl flex items-center justify-center mx-auto mb-2">
            @svg('heroicon-o-shopping-bag', 'w-5 h-5 text-[#5C4A35]')
        </div>
        <p class="text-[#9e8065] text-xs">Total Produk</p>
        <p class="font-bold text-[#3E2F1E] text-2xl mt-1">{{ $totalProducts }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
        <div class="w-10 h-10 bg-[#f5e6d3] rounded-xl flex items-center justify-center mx-auto mb-2">
            @svg('heroicon-o-users', 'w-5 h-5 text-[#5C4A35]')
        </div>
        <p class="text-[#9e8065] text-xs">Total User</p>
        <p class="font-bold text-[#3E2F1E] text-2xl mt-1">{{ $totalUsers }}</p>
    </div>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] overflow-hidden">
    <div class="px-6 py-4 border-b border-[#f0e0cc] flex items-center gap-2">
        @svg('heroicon-o-clock', 'w-5 h-5 text-[#5C4A35]')
        <h3 class="font-bold text-[#3E2F1E]">Transaksi Terakhir</h3>
    </div>
    <table class="w-full">
        <thead class="bg-[#f5e6d3]">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">#</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Kasir</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Total</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Bayar</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Kembalian</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e0cc]">
            @foreach($recentTransactions as $trx)
            <tr class="hover:bg-[#fdf5ec] transition">
                <td class="px-4 py-3 text-sm text-[#9e8065]">#{{ $trx->id }}</td>
                <td class="px-4 py-3 text-sm text-[#3E2F1E]">{{ $trx->user->name }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-[#5C4A35]">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                <td class="px-4 py-3 text-sm text-[#5C4A35]">Rp {{ number_format($trx->paid, 0, ',', '.') }}</td>
                <td class="px-4 py-3 text-sm text-[#5C4A35]">Rp {{ number_format($trx->change, 0, ',', '.') }}</td>
                <td class="px-4 py-3 text-sm text-[#9e8065]">{{ $trx->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
