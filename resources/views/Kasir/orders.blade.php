@extends('layouts.app')

@section('title', 'Pesanan Masuk - Kasir')


@section('content')
{{ dd($orders->count(), $orders->pluck('id')) }}

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">🔔 Pesanan Masuk</h2>
    <a href="{{ route('kasir.dashboard') }}" class="text-gray-400 hover:text-orange-500 text-sm">← Kembali</a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
@endif

@if($orders->isEmpty())
    <div class="bg-white rounded-xl p-10 text-center text-gray-400">
        <p class="text-4xl mb-3">🎉</p>
        <p>Tidak ada pesanan masuk saat ini</p>
    </div>
@else
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="font-bold text-gray-800">{{ $order->customer_name }}</h3>
                    <p class="text-gray-400 text-sm">
                        {{ $order->table_number ?? 'Tanpa meja' }} • 
                        {{ $order->created_at->diffForHumans() }}
                    </p>
                    @if($order->note)
                        <p class="text-orange-500 text-sm mt-1">📝 {{ $order->note }}</p>
                    @endif
                </div>
                <div class="text-right space-y-1">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-600
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-600
                        @else bg-green-100 text-green-600 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                    <br>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                        @if($order->payment_status === 'paid') bg-green-100 text-green-600
                        @else bg-yellow-100 text-yellow-600 @endif">
                        💳 {{ $order->payment_status === 'paid' ? 'Lunas' : ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            {{-- Items --}}
            <div class="border-t pt-3 mb-3 space-y-1">
                @foreach($order->items as $item)
                <div class="flex justify-between text-sm text-gray-600">
                    <span>{{ $item->product->name }} x{{ $item->qty }}</span>
                    <span>Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                </div>
                @endforeach
                <div class="flex justify-between font-bold text-orange-500 pt-2 border-t">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Aksi --}}
            <div class="flex gap-2">
                @if($order->status === 'pending')
                <form action="{{ route('kasir.orders.confirm', $order) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="status" value="confirmed">
                    <button class="w-full bg-blue-500 text-white py-2 rounded-lg text-sm hover:bg-blue-600">
                        ✅ Konfirmasi
                    </button>
                </form>
                @elseif($order->status === 'confirmed')
                <form action="{{ route('kasir.orders.confirm', $order) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="status" value="ready">
                    <button class="w-full bg-green-500 text-white py-2 rounded-lg text-sm hover:bg-green-600">
                        🍽️ Siap Diantar
                    </button>
                </form>
                @elseif($order->status === 'ready')
                <form action="{{ route('kasir.orders.confirm', $order) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="status" value="done">
                    <button class="w-full bg-gray-500 text-white py-2 rounded-lg text-sm hover:bg-gray-600">
                        🎉 Selesai
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- Auto refresh --}}
<script>setTimeout(() => location.reload(), 15000);</script>

@endsection