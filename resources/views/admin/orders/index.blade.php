@extends('layouts.app')

@section('title', 'Pesanan - Admin')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">🔔 Semua Pesanan</h2>
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-orange-500 text-sm">← Kembali</a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-sm text-gray-600">#</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Pelanggan</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Meja</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Total</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Status</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($orders as $order)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-400">#{{ $order->id }}</td>
                <td class="px-4 py-3 font-semibold text-gray-800">{{ $order->customer_name }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $order->table_number ?? '-' }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-orange-500">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-600
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-600
                        @elseif($order->status === 'ready') bg-green-100 text-green-600
                        @else bg-gray-100 text-gray-600 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-400">{{ $order->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection