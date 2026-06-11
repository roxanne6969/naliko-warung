@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - Kasir')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-credit-card', 'w-6 h-6 text-[#5C4A35]')
        <div>
            <h2 class="text-2xl font-bold text-[#3E2F1E]">Verifikasi Pembayaran</h2>
            <p class="text-[#9e8065] text-sm">Cek dan verifikasi bukti bayar dari pelanggan</p>
        </div>
    </div>
    <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-1 text-[#9e8065] hover:text-[#5C4A35] text-sm transition">
        @svg('heroicon-o-arrow-left', 'w-4 h-4') Kembali
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-2xl px-4 py-3 mb-6 flex items-center gap-3">
        @svg('heroicon-o-check-circle', 'w-5 h-5 text-green-600')
        <span class="text-green-700 text-sm font-semibold">{{ session('success') }}</span>
    </div>
@endif

@if($orders->isEmpty())
    <div class="bg-white rounded-2xl border border-[#e8d5c1] p-10 text-center shadow-sm">
        <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
            @svg('heroicon-o-check-circle', 'w-8 h-8 text-green-600')
        </div>
        <p class="font-semibold text-[#3E2F1E]">Tidak ada pembayaran yang menunggu verifikasi</p>
        <p class="text-[#9e8065] text-sm mt-1">Semua pembayaran sudah diverifikasi</p>
    </div>
@else
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5">

            {{-- Header --}}
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        @svg('heroicon-o-user', 'w-4 h-4 text-[#9e8065]')
                        <h3 class="font-bold text-[#3E2F1E] text-lg">{{ $order->customer_name }}</h3>
                    </div>
                    <p class="text-[#9e8065] text-sm mt-0.5 ml-6">
                        Order #{{ $order->id }} ·
                        {{ $order->table_number ?? 'Tanpa meja' }} ·
                        {{ $order->updated_at->diffForHumans() }}
                    </p>
                    <div class="ml-6 mt-1">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full font-semibold">
                            @svg('heroicon-o-credit-card', 'w-3 h-3') {{ $order->payment_method }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-[#5C4A35] text-xl">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </p>
                    <span class="inline-flex items-center gap-1 text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-semibold mt-1">
                        @svg('heroicon-o-clock', 'w-3 h-3') Menunggu Verifikasi
                    </span>
                </div>
            </div>

            {{-- Items --}}
            <div class="bg-[#fdfaf7] rounded-xl border border-[#f0e0cc] py-3 px-4 mb-4">
                <p class="text-xs text-[#9e8065] mb-2 font-semibold uppercase tracking-wide">Item Pesanan</p>
                <div class="space-y-1">
                    @foreach($order->items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-[#3E2F1E]">{{ $item->product->name }} × {{ $item->qty }}</span>
                        <span class="text-[#5C4A35] font-semibold">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Bukti Pembayaran --}}
            <div class="mb-4">
                <p class="text-sm font-semibold text-[#3E2F1E] mb-2 flex items-center gap-1">
                    @svg('heroicon-o-photo', 'w-4 h-4 text-[#5C4A35]') Bukti Pembayaran
                </p>
                @if($order->payment_proof)
                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                    <img src="{{ asset('storage/' . $order->payment_proof) }}"
                        alt="Bukti Pembayaran"
                        class="max-h-52 rounded-xl border border-[#e8d5c1] hover:opacity-90 transition cursor-pointer shadow-sm">
                </a>
                <p class="text-xs text-[#9e8065] mt-1 flex items-center gap-1">
                    @svg('heroicon-o-arrow-top-right-on-square', 'w-3 h-3') Klik gambar untuk memperbesar
                </p>
                @else
                <div class="bg-[#fdfaf7] rounded-xl border border-[#f0e0cc] p-4 text-center">
                    <div class="w-10 h-10 bg-[#f5e6d3] rounded-full flex items-center justify-center mx-auto mb-2">
                        @svg('heroicon-o-banknotes', 'w-5 h-5 text-[#5C4A35]')
                    </div>
                    <p class="text-[#9e8065] text-sm">Pembayaran Cash — tidak ada bukti foto</p>
                </div>
                @endif
            </div>

            {{-- Form Approve / Reject --}}
            <form action="{{ route('kasir.payment.verify', $order) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="text-sm text-[#5C4A35] mb-1 block font-semibold">Catatan (opsional)</label>
                    <input type="text" name="payment_note" placeholder="Contoh: Pembayaran sudah diterima"
                        class="w-full border border-[#e8d5c1] rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
                </div>

                <div class="flex gap-2">
                    <button type="submit" name="action" value="approve"
                        class="flex-1 flex items-center justify-center gap-2 bg-green-500 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-green-600 transition">
                        @svg('heroicon-o-check', 'w-4 h-4') Terima Pembayaran
                    </button>
                    <button type="submit" name="action" value="reject"
                        class="flex-1 flex items-center justify-center gap-2 bg-red-500 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-red-600 transition"
                        onclick="return confirm('Tolak pembayaran ini?')">
                        @svg('heroicon-o-x-mark', 'w-4 h-4') Tolak
                    </button>
                </div>
            </form>

        </div>
        @endforeach
    </div>
@endif

{{-- Auto refresh setiap 20 detik --}}
<script>setTimeout(() => location.reload(), 20000);</script>

@endsection