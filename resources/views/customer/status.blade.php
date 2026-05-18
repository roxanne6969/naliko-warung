@extends('layouts.app')

@section('title', 'Status Pesanan - Naliko Warung')

@section('content')

<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-2xl shadow p-6">
        <div class="text-center mb-6">
            @if($order->status === 'pending')
                <div class="text-5xl mb-3">⏳</div>
                <h2 class="text-xl font-bold text-yellow-500">Menunggu Konfirmasi</h2>
            @elseif($order->status === 'confirmed')
                <div class="text-5xl mb-3">👨‍🍳</div>
                <h2 class="text-xl font-bold text-blue-500">Sedang Diproses</h2>
            @elseif($order->status === 'ready')
                <div class="text-5xl mb-3">✅</div>
                <h2 class="text-xl font-bold text-green-500">Pesanan Siap!</h2>
            @elseif($order->status === 'done')
                <div class="text-5xl mb-3">🎉</div>
                <h2 class="text-xl font-bold text-gray-600">Pesanan Selesai</h2>
            @endif
            <p class="text-gray-400 text-sm mt-1">Order #{{ $order->id }}</p>
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

        <a href="{{ route('menu.index') }}" 
            class="block text-center mt-6 bg-orange-500 text-white py-3 rounded-xl hover:bg-orange-600 transition">
            🍽️ Pesan Lagi
        </a>
    </div>
</div>

{{-- Auto refresh setiap 10 detik --}}
<script>
    @if($order->status !== 'done')
        setTimeout(() => location.reload(), 10000);
    @endif
</script>

@endsection