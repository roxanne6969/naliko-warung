@extends('layouts.app')

@section('title', 'Status Pesanan - Naliko Warung')

@section('content')

<div class="max-w-lg mx-auto">

    {{-- Flash success message --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4 flex gap-3">
        <span class="text-2xl">✅</span>
        <p class="text-sm text-green-600 font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow p-6">

        {{-- Order Status Header --}}
        <div class="text-center mb-6">
            @if($order->status === 'pending' && $order->payment_status === 'unpaid')
                <div class="text-5xl mb-3">💳</div>
                <h2 class="text-xl font-bold text-yellow-500">Menunggu Pembayaran</h2>
                <p class="text-gray-400 text-xs mt-1">Silakan lakukan pembayaran terlebih dahulu</p>
            @elseif($order->status === 'pending' && $order->payment_status === 'waiting_verification')
                <div class="text-5xl mb-3">🔍</div>
                <h2 class="text-xl font-bold text-blue-500">Menunggu Verifikasi Kasir</h2>
                <p class="text-gray-400 text-xs mt-1">Bukti pembayaran sedang diperiksa</p>
            @elseif($order->status === 'pending' && $order->payment_status === 'rejected')
                <div class="text-5xl mb-3">❌</div>
                <h2 class="text-xl font-bold text-red-500">Pembayaran Ditolak</h2>
                <p class="text-gray-400 text-xs mt-1">{{ $order->payment_note ?? 'Silakan upload ulang bukti pembayaran.' }}</p>
            @elseif($order->status === 'pending' && $order->payment_status === 'paid')
                <div class="text-5xl mb-3">⏳</div>
                <h2 class="text-xl font-bold text-yellow-500">Menunggu Konfirmasi Kasir</h2>
                <p class="text-gray-400 text-xs mt-1">Pembayaran diterima, pesanan menunggu dikonfirmasi</p>
            @elseif($order->status === 'confirmed')
                <div class="text-5xl mb-3">👨‍🍳</div>
                <h2 class="text-xl font-bold text-blue-500">Sedang Diproses</h2>
                <p class="text-gray-400 text-xs mt-1">Pesanan sedang disiapkan</p>
            @elseif($order->status === 'ready')
                <div class="text-5xl mb-3">✅</div>
                <h2 class="text-xl font-bold text-green-500">Pesanan Siap!</h2>
                <p class="text-gray-400 text-xs mt-1">Silakan ambil pesanan Anda</p>
            @elseif($order->status === 'done')
                <div class="text-5xl mb-3">🎉</div>
                <h2 class="text-xl font-bold text-gray-600">Pesanan Selesai</h2>
                <p class="text-gray-400 text-xs mt-1">Terima kasih sudah memesan!</p>
            @endif
            <p class="text-gray-400 text-sm mt-2">Order #{{ $order->id }}</p>
        </div>

        {{-- Payment Status Badge --}}
        <div class="flex justify-center mb-4">
            @if($order->payment_status === 'unpaid')
                <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-semibold">💳 Belum Bayar</span>
            @elseif($order->payment_status === 'waiting_verification')
                <span class="px-3 py-1 bg-yellow-100 text-yellow-600 rounded-full text-xs font-semibold">🔍 Menunggu Verifikasi</span>
            @elseif($order->payment_status === 'paid')
                <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs font-semibold">✅ Lunas</span>
            @elseif($order->payment_status === 'rejected')
                <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-semibold">❌ Ditolak</span>
            @endif
        </div>

        {{-- Info Pesanan --}}
        <div class="bg-gray-50 rounded-xl p-4 mb-4">
            <p class="text-sm text-gray-600">👤 <strong>{{ $order->customer_name }}</strong></p>
            @if($order->table_number)
                <p class="text-sm text-gray-600 mt-1">🪑 {{ $order->table_number }}</p>
            @endif
            @if($order->note)
                <p class="text-sm text-gray-600 mt-1">📝 {{ $order->note }}</p>
            @endif
        </div>

        {{-- Item Pesanan --}}
        <div class="space-y-2 mb-4">
            @foreach($order->items as $item)
            <div class="flex justify-between text-sm">
                <span class="text-gray-700">{{ $item->product->name }} x{{ $item->qty }}</span>
                <span class="font-semibold">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <div class="border-t pt-3 flex justify-between font-bold text-orange-500">
            <span>Total</span>
            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-6 space-y-3">
            @if($order->payment_status === 'unpaid' || $order->payment_status === 'rejected')
                <a href="{{ route('order.payment', $order) }}"
                    class="block text-center bg-orange-500 text-white py-3 rounded-xl font-bold hover:bg-orange-600 transition">
                    💳 Bayar Sekarang
                </a>
            @endif

            <a href="{{ route('menu.index') }}"
                class="block text-center border border-gray-300 text-gray-600 py-3 rounded-xl hover:bg-gray-50 transition">
                🍽️ Pesan Lagi
            </a>
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