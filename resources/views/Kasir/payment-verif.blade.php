@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - Kasir')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">💳 Verifikasi Pembayaran</h2>
    <a href="{{ route('kasir.dashboard') }}" class="text-gray-400 hover:text-orange-500 text-sm">← Kembali</a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
@endif

@if($orders->isEmpty())
    <div class="bg-white rounded-xl p-10 text-center text-gray-400">
        <p class="text-4xl mb-3">✅</p>
        <p>Tidak ada pembayaran yang menunggu verifikasi</p>
    </div>
@else
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-xl shadow p-5">

            {{-- Header --}}
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="font-bold text-gray-800">{{ $order->customer_name }}</h3>
                    <p class="text-gray-400 text-sm">
                        Order #{{ $order->id }} ·
                        {{ $order->table_number ?? 'Tanpa meja' }} ·
                        {{ $order->updated_at->diffForHumans() }}
                    </p>
                    <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-600 text-xs rounded-full font-semibold">
                        {{ $order->payment_method }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="font-bold text-orange-500 text-lg">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </p>
                    <span class="text-xs bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full">
                        Menunggu Verifikasi
                    </span>
                </div>
            </div>

            {{-- Items --}}
            <div class="border-t border-b py-3 mb-3 space-y-1">
                @foreach($order->items as $item)
                <div class="flex justify-between text-sm text-gray-600">
                    <span>{{ $item->product->name }} × {{ $item->qty }}</span>
                    <span>Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            {{-- Bukti Pembayaran --}}
            <div class="mb-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">Bukti Pembayaran:</p>
                @if($order->payment_proof)
                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                    <img src="{{ asset('storage/' . $order->payment_proof) }}"
                        alt="Bukti Pembayaran"
                        class="max-h-48 rounded-xl border hover:opacity-90 transition cursor-pointer">
                </a>
                <p class="text-xs text-gray-400 mt-1">Klik gambar untuk memperbesar</p>
                @else
                <div class="bg-gray-50 rounded-xl p-4 text-center text-gray-400">
                    <p class="text-2xl mb-1">💵</p>
                    <p class="text-sm">Pembayaran Cash — tidak ada bukti foto</p>
                </div>
                @endif
            </div>

            {{-- Form Approve / Reject --}}
            <form action="{{ route('kasir.payment.verify', $order) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="text-sm text-gray-600 mb-1 block">Catatan (opsional)</label>
                    <input type="text" name="payment_note" placeholder="Contoh: Pembayaran sudah diterima"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>

                <div class="flex gap-2">
                    <button type="submit" name="action" value="approve"
                        class="flex-1 bg-green-500 text-white py-2 rounded-lg text-sm font-semibold hover:bg-green-600 transition">
                        ✅ Terima Pembayaran
                    </button>
                    <button type="submit" name="action" value="reject"
                        class="flex-1 bg-red-500 text-white py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition"
                        onclick="return confirm('Tolak pembayaran ini?')">
                        ❌ Tolak
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