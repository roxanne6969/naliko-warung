@extends('layouts.app')

@section('title', 'Status Pesanan - Naliko Warung')

@section('content')

<div class="max-w-lg mx-auto">

    {{-- Flash success message --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-5 flex gap-3">
        @svg('heroicon-o-check-circle', 'w-5 h-5 text-green-600 flex-shrink-0 mt-0.5')
        <p class="text-sm text-green-700 font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] overflow-hidden">

        {{-- Order Status Header --}}
        <div class="bg-[#5C4A35] px-6 py-5 text-center">
            @if($order->status === 'pending' && $order->payment_status === 'unpaid')
                <div class="w-16 h-16 bg-yellow-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                    @svg('heroicon-o-credit-card', 'w-8 h-8 text-yellow-300')
                </div>
                <h2 class="text-xl font-bold text-yellow-300">Menunggu Pembayaran</h2>
                <p class="text-[#dbc7a9] text-xs mt-1">Silakan lakukan pembayaran terlebih dahulu</p>
            @elseif($order->status === 'pending' && $order->payment_status === 'waiting_verification')
                <div class="w-16 h-16 bg-blue-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                    @svg('heroicon-o-magnifying-glass', 'w-8 h-8 text-blue-300')
                </div>
                <h2 class="text-xl font-bold text-blue-300">Menunggu Verifikasi Kasir</h2>
                <p class="text-[#dbc7a9] text-xs mt-1">Bukti pembayaran sedang diperiksa</p>
            @elseif($order->status === 'pending' && $order->payment_status === 'rejected')
                <div class="w-16 h-16 bg-red-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                    @svg('heroicon-o-x-circle', 'w-8 h-8 text-red-300')
                </div>
                <h2 class="text-xl font-bold text-red-300">Pembayaran Ditolak</h2>
                <p class="text-[#dbc7a9] text-xs mt-1">{{ $order->payment_note ?? 'Silakan upload ulang bukti pembayaran.' }}</p>
            @elseif($order->status === 'pending' && $order->payment_status === 'paid')
                <div class="w-16 h-16 bg-yellow-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                    @svg('heroicon-o-clock', 'w-8 h-8 text-yellow-300')
                </div>
                <h2 class="text-xl font-bold text-yellow-300">Menunggu Konfirmasi Kasir</h2>
                <p class="text-[#dbc7a9] text-xs mt-1">Pembayaran diterima, pesanan menunggu dikonfirmasi</p>
            @elseif($order->status === 'confirmed')
                <div class="w-16 h-16 bg-blue-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                    @svg('heroicon-o-fire', 'w-8 h-8 text-blue-300')
                </div>
                <h2 class="text-xl font-bold text-blue-300">Sedang Diproses</h2>
                <p class="text-[#dbc7a9] text-xs mt-1">Pesanan sedang disiapkan</p>
            @elseif($order->status === 'ready')
                <div class="w-16 h-16 bg-green-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                    @svg('heroicon-o-check-badge', 'w-8 h-8 text-green-300')
                </div>
                <h2 class="text-xl font-bold text-green-300">Pesanan Siap!</h2>
                <p class="text-[#dbc7a9] text-xs mt-1">Silakan ambil pesanan Anda</p>
            @elseif($order->status === 'done')
                <div class="w-16 h-16 bg-green-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                    @svg('heroicon-o-check-circle', 'w-8 h-8 text-green-300')
                </div>
                <h2 class="text-xl font-bold text-green-300">Pesanan Selesai</h2>
                <p class="text-[#dbc7a9] text-xs mt-1">Terima kasih sudah memesan!</p>
            @endif
            <p class="text-[#9e8065] text-xs mt-2">Order #{{ $order->id }}</p>
        </div>

        <div class="p-6">

            {{-- Payment Status Badge --}}
            <div class="flex justify-center mb-5">
                @if($order->payment_status === 'unpaid')
                    <span class="flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                        @svg('heroicon-o-credit-card', 'w-3.5 h-3.5') Belum Bayar
                    </span>
                @elseif($order->payment_status === 'waiting_verification')
                    <span class="flex items-center gap-1 px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                        @svg('heroicon-o-clock', 'w-3.5 h-3.5') Menunggu Verifikasi
                    </span>
                @elseif($order->payment_status === 'paid')
                    <span class="flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                        @svg('heroicon-o-check-circle', 'w-3.5 h-3.5') Lunas
                    </span>
                @elseif($order->payment_status === 'rejected')
                    <span class="flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                        @svg('heroicon-o-x-circle', 'w-3.5 h-3.5') Ditolak
                    </span>
                @endif
            </div>

            {{-- Info Pesanan --}}
            <div class="bg-[#fdfaf7] rounded-xl border border-[#f0e0cc] p-4 mb-4 space-y-2">
                <div class="flex items-center gap-2 text-sm">
                    @svg('heroicon-o-user', 'w-4 h-4 text-[#9e8065]')
                    <span class="font-semibold text-[#3E2F1E]">{{ $order->customer_name }}</span>
                </div>
                @if($order->table_number)
                    <div class="flex items-center gap-2 text-sm">
                        @svg('heroicon-o-table-cells', 'w-4 h-4 text-[#9e8065]')
                        <span class="text-[#5C4A35]">{{ $order->table_number }}</span>
                    </div>
                @endif
                @if($order->note)
                    <div class="flex items-center gap-2 text-sm">
                        @svg('heroicon-o-document-text', 'w-4 h-4 text-[#9e8065]')
                        <span class="text-[#5C4A35]">{{ $order->note }}</span>
                    </div>
                @endif
            </div>

            {{-- Item Pesanan --}}
            <div class="space-y-2 mb-4">
                @foreach($order->items as $item)
                <div class="flex justify-between text-sm py-1.5 border-b border-[#f0e0cc] last:border-0">
                    <span class="text-[#3E2F1E]">{{ $item->product->name }} ×{{ $item->qty }}</span>
                    <span class="font-semibold text-[#5C4A35]">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="flex justify-between font-bold text-[#5C4A35] bg-[#fdfaf7] rounded-xl border border-[#f0e0cc] px-4 py-3 mb-5">
                <span>Total</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-3">
                @if($order->payment_status === 'unpaid' || $order->payment_status === 'rejected')
                    <a href="{{ route('order.payment', $order) }}"
                        class="flex items-center justify-center gap-2 w-full bg-[#5C4A35] text-[#F7E6CC] py-3 rounded-xl font-bold hover:bg-[#3E2F1E] transition">
                        @svg('heroicon-o-credit-card', 'w-4 h-4') Bayar Sekarang
                    </a>
                @endif

                <a href="{{ route('menu.index') }}"
                    class="flex items-center justify-center gap-2 w-full border border-[#e8d5c1] text-[#9e8065] py-3 rounded-xl hover:bg-[#fdf5ec] hover:text-[#5C4A35] transition text-sm">
                    @svg('heroicon-o-shopping-bag', 'w-4 h-4') Pesan Lagi
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Auto refresh setiap 10 detik (hanya jika pesanan belum selesai) --}}
<script>
    @if($order->status !== 'done')
        setTimeout(() => location.reload(), 10000);
    @endif
</script>

@endsection