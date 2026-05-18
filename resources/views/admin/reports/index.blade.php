@extends('layouts.app')

@section('title', 'Laporan - Admin')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">📈 Laporan Penjualan</h2>
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-orange-500 text-sm">← Kembali</a>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow p-5 text-center">
        <p class="text-gray-400 text-sm">Total Pendapatan</p>
        <p class="font-bold text-orange-500 text-lg mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 text-center">
        <p class="text-gray-400 text-sm">Total Transaksi</p>
        <p class="font-bold text-gray-800 text-xl mt-1">{{ $totalTransactions }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 text-center">
        <p class="text-gray-400 text-sm">Total Produk</p>
        <p class="font-bold text-gray-800 text-xl mt-1">{{ $totalProducts }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 text-center">
        <p class="text-gray-400 text-sm">Total User</p>
        <p class="font-bold text-gray-800 text-xl mt-1">{{ $totalUsers }}</p>
    </div>
</div>

{{-- Transaksi Terakhir --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h3 class="font-bold text-gray-800">Transaksi Terakhir</h3>
    </div>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-sm text-gray-600">#</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Kasir</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Total</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Bayar</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Kembalian</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($recentTransactions as $trx)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-400">#{{ $trx->id }}</td>
                <td class="px-4 py-3 text-sm text-gray-800">{{ $trx->user->name }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-orange-500">
                    Rp {{ number_format($trx->total, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    Rp {{ number_format($trx->paid, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    Rp {{ number_format($trx->change, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-400">{{ $trx->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection