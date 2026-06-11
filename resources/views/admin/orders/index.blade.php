{{-- ===================== ORDERS/INDEX.BLADE.PHP ===================== --}}
@extends('layouts.app')
@section('title', 'Pesanan - Admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-bell', 'w-6 h-6 text-[#5C4A35]')
        <h2 class="text-2xl font-bold text-[#3E2F1E]">Semua Pesanan</h2>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 text-[#9e8065] hover:text-[#5C4A35] text-sm transition">
        @svg('heroicon-o-arrow-left', 'w-4 h-4') Kembali
    </a>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] overflow-hidden">
    <table class="w-full">
        <thead class="bg-[#f5e6d3]">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">#</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Pelanggan</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Meja</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Total</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Status</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e0cc]">
            @foreach($orders as $order)
            <tr class="hover:bg-[#fdf5ec] transition">
                <td class="px-4 py-3 text-sm text-[#9e8065]">#{{ $order->id }}</td>
                <td class="px-4 py-3 font-semibold text-[#3E2F1E]">{{ $order->customer_name }}</td>
                <td class="px-4 py-3 text-sm text-[#5C4A35]">{{ $order->table_number ?? '-' }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-[#5C4A35]">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-700
                        @elseif($order->status === 'ready') bg-green-100 text-green-700
                        @else bg-[#f0e0cc] text-[#5C4A35] @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-[#9e8065]">{{ $order->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection