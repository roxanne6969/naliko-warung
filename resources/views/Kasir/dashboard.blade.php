@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-6">👋 Selamat datang, {{ auth()->user()->name }}</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <a href="{{ route('kasir.orders') }}" 
        class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition flex items-center gap-4">
        <div class="bg-yellow-100 rounded-xl p-4 text-3xl">🔔</div>
        <div>
            <h3 class="font-bold text-gray-800 text-lg">Pesanan Masuk</h3>
            <p class="text-gray-400 text-sm">Lihat & konfirmasi pesanan pelanggan</p>
        </div>
    </a>

    <a href="{{ route('kasir.transaction') }}" 
        class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition flex items-center gap-4">
        <div class="bg-orange-100 rounded-xl p-4 text-3xl">💰</div>
        <div>
            <h3 class="font-bold text-gray-800 text-lg">Transaksi Manual</h3>
            <p class="text-gray-400 text-sm">Input transaksi langsung di kasir</p>
        </div>
    </a>
</div>

@endsection