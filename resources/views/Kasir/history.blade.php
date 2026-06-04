@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Kasir')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">📋 Riwayat Pesanan</h2>
    <a href="{{ route('kasir.dashboard') }}" class="text-gray-400 hover:text-orange-500 text-sm">← Kembali</a>
</div>

{{-- Filter & Search --}}
<div class="bg-white rounded-2xl shadow p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <input type="text" id="search" placeholder="🔍 Cari nama kasir..."
            oninput="filterTable()"
            class="border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
        <input type="date" id="filter-date" onchange="filterTable()"
            class="border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
        <select id="filter-metode" onchange="filterTable()"
            class="border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            <option value="">Semua Metode</option>
            <option value="Cash">Cash</option>
            <option value="QRIS">QRIS</option>
            <option value="Debit">Debit</option>
        </select>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-gray-400 text-xs mb-1">Total Transaksi</p>
        <p class="font-bold text-2xl text-gray-800">{{ $transactions->total() }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-gray-400 text-xs mb-1">Total Pendapatan</p>
        <p class="font-bold text-lg text-orange-500">Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-gray-400 text-xs mb-1">Transaksi Hari Ini</p>
        <p class="font-bold text-2xl text-green-600">{{ \App\Models\Transaction::whereDate('created_at', today())->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-gray-400 text-xs mb-1">Pendapatan Hari Ini</p>
        <p class="font-bold text-lg text-green-600">Rp {{ number_format(\App\Models\Transaction::whereDate('created_at', today())->sum('total'), 0, ',', '.') }}</p>
    </div>
</div>

{{-- Tabel Transaksi --}}
<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full" id="trx-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-sm text-gray-600">#</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Kasir</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Item</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Total</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Bayar</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Kembalian</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Metode</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Waktu</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y" id="trx-body">
            @forelse($transactions as $trx)
            <tr class="hover:bg-gray-50 trx-row"
                data-kasir="{{ strtolower($trx->user->name) }}"
                data-date="{{ $trx->created_at->format('Y-m-d') }}"
                data-metode="{{ $trx->metode ?? 'Cash' }}">
                <td class="px-4 py-3 text-sm text-gray-400">#{{ $trx->id }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $trx->user->name }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    {{ $trx->items->sum('qty') }} item
                </td>
                <td class="px-4 py-3 text-sm font-bold text-orange-500">
                    Rp {{ number_format($trx->total, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    Rp {{ number_format($trx->paid, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    Rp {{ number_format($trx->change, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        @if(($trx->metode ?? 'Cash') === 'Cash') bg-green-100 text-green-600
                        @elseif(($trx->metode ?? '') === 'QRIS') bg-blue-100 text-blue-600
                        @else bg-purple-100 text-purple-600 @endif">
                        {{ $trx->metode ?? 'Cash' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-400">
                    <p>{{ $trx->created_at->format('d/m/Y') }}</p>
                    <p class="text-xs">{{ $trx->created_at->format('H:i') }}</p>
                </td>
                <td class="px-4 py-3">
                    <button onclick="lihatDetail({{ $trx->id }})"
                        class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-lg hover:bg-orange-50 hover:text-orange-500 mr-1">
                        👁️ Detail
                    </button>
                    <button onclick="cetakStruk({{ $trx->id }})"
                        class="te