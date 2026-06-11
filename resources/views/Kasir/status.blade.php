@extends('layouts.app')

@section('title', 'Status Pesanan - Naliko Warung')

@section('content')

<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-2xl shadow p-6">

        {{-- Status Icon --}}
        <div class="text-center mb-6">
            @if($order->payment_status === 'waiting_verification')
                <div class="text-5xl mb-3">🕐</div>
                <h2 class="text-xl font-bold text-blue-500">Menunggu Verifikasi Pembayaran</h2>
                <p class="text-gray-400 text-sm mt-1">Kasir sedang memeriksa bukti pembayaranmu</p>
            @elseif($order->payment_status === 'rejected')
                <div class="text-5xl mb-3">❌</div>
                <h2 class="text-xl font-bold text-red-500">Pembayaran Ditolak</h2>
                <p class="text-gray-400 text-sm mt-1">{{ $order->payment_note ?? 'Silakan upload ulang bukti pembayaran' }}</p>
            @elseif($order->payment_status === 'paid' || $order->status === 'done')
                <div class="text-5xl mb-3">🎉</div>
                <h2 class="text-xl font-bold text-green-500">Pesanan Selesai!</h2>
                <p class="text-gray-400 text-sm mt-1">Terima kasih sudah memesan di Naliko Warung</p>
            @elseif($order->status === 'ready')
                <div class="text-5xl mb-3">✅</div>
                <h2 class="text-xl font-bold text-green-500">Pesanan Siap!</h2>
                <p class="text-gray-400 text-sm mt-1">Silakan lakukan pembayaran</p>
            @elseif($order->status === 'confirmed')
                <div class="text-5xl mb-3">👨‍🍳</div>
                <h2 class="text-xl font-bold text-blue-500">Sedang Diproses</h2>
                <p class="text-gray-400 text-sm mt-1">Pesananmu sedang disiapkan</p>
            @else
                <div class="text-5xl mb-3">⏳</div>
                <h2 class="text-xl font-bold text-yellow-500">Menunggu Konfirmasi</h2>
                <p class="text-gray-400 text-sm mt-1">Kasir akan segera memproses pesananmu</p>
            @endif
            <p class="text-gray-300 text-xs mt-2">Order #{{ $order->id }}</p>
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
                <span class="text-gray-700">{{ $item->product->name }} × {{ $item->qty }}</span>
                <span class="font-semibold">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <div class="border-t pt-3 flex justify-between font-bold text-orange-500 mb-5">
            <span>Total</span>
            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>

        {{-- Tombol Bayar - muncul hanya saat ready atau ditolak --}}
        @if($order->status === 'ready' && in_array($order->payment_status, ['unpaid', 'rejected']))
            <a href="{{ route('order.payment', $order) }}"
                class="block text-center bg-orange-500 text-white py-3 rounded-xl font-bold hover:bg-orange-600 transition mb-3">
                💳 Bayar Sekarang
            </a>
        @endif

        @if(!in_array($order->status, ['done']) || $order->payment_status !== 'paid')
            <a href="{{ route('menu.index') }}"
                class="block text-center mt-2 text-gray-400 text-sm hover:text-orange-500">
                🍽️ Pesan Lagi
            </a>
        @else
            <a href="{{ route('menu.index') }}"
                class="block text-center bg-orange-500 text-white py-3 rounded-xl hover:bg-orange-600 transition">
                🍽️ Pesan Lagi
            </a>
        @endif

    </div>
</div>

{{-- Auto refresh --}}
<script>
    @if(!in_array($order->status, ['done']) && $order->payment_status !== 'paid')
        setTimeout(() => location.reload(), 10000);
    @endif
</script>

@endsection