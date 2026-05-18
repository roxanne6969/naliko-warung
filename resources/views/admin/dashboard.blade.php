@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="space-y-8">

    {{-- Header Bar --}}
    <div class="flex items-center justify-between rounded-2xl bg-[#7A6247] px-6 py-4 text-[#F7E6CC] shadow-sm ring-1 ring-black/5">
        <div>
            <h2 class="text-lg font-semibold tracking-wide">Dashboard Admin</h2>
            <p class="text-sm text-[#EBD9BD]">Selamat datang di panel admin Naliko Warung</p>
        </div>
        <div class="hidden sm:flex h-10 w-10 items-center justify-center rounded-full border border-[#D7C2A0] text-[#F7E6CC]">
            <span class="text-lg font-semibold">A</span>
        </div>
    </div>

    {{-- Stats Cards --}}
    <section class="rounded-[2rem] bg-[#F5E2C7] p-6 sm:p-8 shadow-[0_20px_50px_rgba(0,0,0,0.08)] ring-1 ring-black/5">
        <div class="text-center">
            <h3 class="text-xl font-bold text-[#2F2418] tracking-[0.18em] uppercase">Informasi Hari Ini</h3>
        </div>

        <div class="mt-8 grid gap-6 grid-cols-2 md:grid-cols-4">
            <a href="{{ route('products.index') }}"
                class="rounded-2xl bg-[#9C8462] px-6 py-8 text-center text-[#F5E6D0] shadow-md hover:bg-[#7A6247] transition">
                <div class="text-3xl mb-2">🛍️</div>
                <div class="text-xl font-semibold">Produk</div>
                <div class="my-4 border-t border-[#F5E6D0]/40"></div>
                <p class="text-2xl font-bold">{{ \App\Models\Product::count() }}</p>
                <p class="text-sm mt-1 text-[#F1E1C9]">Total produk tersedia</p>
            </a>

            <a href="{{ route('categories.index') }}"
                class="rounded-2xl bg-[#9C8462] px-6 py-8 text-center text-[#F5E6D0] shadow-md hover:bg-[#7A6247] transition">
                <div class="text-3xl mb-2">📁</div>
                <div class="text-xl font-semibold">Kategori</div>
                <div class="my-4 border-t border-[#F5E6D0]/40"></div>
                <p class="text-2xl font-bold">{{ \App\Models\Category::count() }}</p>
                <p class="text-sm mt-1 text-[#F1E1C9]">Total kategori aktif</p>
            </a>

            <a href="{{ route('admin.orders') }}"
                class="rounded-2xl bg-[#9C8462] px-6 py-8 text-center text-[#F5E6D0] shadow-md hover:bg-[#7A6247] transition">
                <div class="text-3xl mb-2">🔔</div>
                <div class="text-xl font-semibold">Pesanan</div>
                <div class="my-4 border-t border-[#F5E6D0]/40"></div>
                <p class="text-2xl font-bold">{{ \App\Models\Order::count() }}</p>
                <p class="text-sm mt-1 text-[#F1E1C9]">Pesanan yang masuk</p>
            </a>

            <a href="{{ route('admin.reports') }}"
                class="rounded-2xl bg-[#9C8462] px-6 py-8 text-center text-[#F5E6D0] shadow-md hover:bg-[#7A6247] transition">
                <div class="text-3xl mb-2">💰</div>
                <div class="text-xl font-semibold">Pendapatan</div>
                <div class="my-4 border-t border-[#F5E6D0]/40"></div>
                <p class="text-lg font-bold">Rp {{ number_format(\App\Models\Transaction::sum('total'), 0, ',', '.') }}</p>
                <p class="text-sm mt-1 text-[#F1E1C9]">Total transaksi</p>
            </a>
        </div>
    </section>

    {{-- Quick Actions --}}
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('users.index') }}"
            class="flex items-center gap-4 rounded-2xl bg-[#F5E2C7] p-6 shadow-[0_20px_50px_rgba(0,0,0,0.08)] ring-1 ring-black/5 hover:bg-[#EDD5B3] transition">
            <div class="rounded-xl bg-[#9C8462] p-4 text-3xl">👥</div>
            <div>
                <h3 class="font-bold text-[#2F2418]">Kelola User</h3>
                <p class="text-sm text-[#7A6247]">Tambah & hapus admin/kasir</p>
            </div>
        </a>

        <a href="{{ route('admin.reports') }}"
            class="flex items-center gap-4 rounded-2xl bg-[#F5E2C7] p-6 shadow-[0_20px_50px_rgba(0,0,0,0.08)] ring-1 ring-black/5 hover:bg-[#EDD5B3] transition">
            <div class="rounded-xl bg-[#9C8462] p-4 text-3xl">📈</div>
            <div>
                <h3 class="font-bold text-[#2F2418]">Laporan Penjualan</h3>
                <p class="text-sm text-[#7A6247]">Lihat rekap transaksi</p>
            </div>
        </a>
    </section>

</div>

@endsection