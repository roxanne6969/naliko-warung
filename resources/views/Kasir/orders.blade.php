@extends('layouts.app')

@section('title', 'Pesanan Online - Kasir')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">🔔 Pesanan Online</h2>
    <a href="{{ route('kasir.dashboard') }}" class="text-gray-400 hover:text-orange-500 text-sm">← Kembali</a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        ✅ {{ session('success') }}
    </div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-gray-400 text-xs mb-1">Pending</p>
        <p class="font-bold text-2xl text-yellow-500">{{ $orders->where('status', 'pending')->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-gray-400 text-xs mb-1">Diproses</p>
        <p class="font-bold text-2xl text-blue-500">{{ $orders->where('status', 'confirmed')->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-gray-400 text-xs mb-1">Siap Diantar</p>
        <p class="font-bold text-2xl text-green-500">{{ $orders->where('status', 'ready')->count() }}</p>
    </div>
</div>

@if($orders->isEmpty())
    <div class="bg-white rounded-2xl p-10 text-center text-gray-400 shadow">
        <p class="text-5xl mb-3">🎉</p>
        <p class="font-semibold">Tidak ada pesanan aktif saat ini</p>
        <p class="text-sm mt-1">Pesanan baru akan muncul di sini secara otomatis</p>
    </div>
@else
    {{-- Filter Tab --}}
    <div class="flex gap-2 mb-4">
        <button onclick="filterStatus('all', this)"
            class="status-btn px-4 py-1.5 rounded-full bg-[#7A6247] text-white text-sm">
            Semua
        </button>
        <button onclick="filterStatus('pending', this)"
            class="status-btn px-4 py-1.5 rounded-full bg-white text-gray-600 text-sm shadow border">
            ⏳ Pending
        </button>
        <button onclick="filterStatus('confirmed', this)"
            class="status-btn px-4 py-1.5 rounded-full bg-white text-gray-600 text-sm shadow border">
            👨‍🍳 Diproses
        </button>
        <button onclick="filterStatus('ready', this)"
            class="status-btn px-4 py-1.5 rounded-full bg-white text-gray-600 text-sm shadow border">
            ✅ Siap Diantar
        </button>
    </div>

    <div class="space-y-4" id="orders-container">
        @foreach($orders as $order)
        <div class="bg-white rounded-2xl shadow p-5 order-card" data-status="{{ $order->status }}">
            
            {{-- Header --}}
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-gray-800 text-lg">{{ $order->customer_name }}</h3>
                        <span class="text-gray-400 text-sm">#{{ $order->id }}</span>
                    </div>
                    <div class="flex gap-3 text-gray-400 text-xs mt-1">
                        <span>🪑 {{ $order->table_number ?? 'Tanpa meja' }}</span>
                        <span>🕐 {{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    @if($order->note)
                        <p class="text-[#7A6247] text-sm mt-1">📝 {{ $order->note }}</p>
                    @endif
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-600
                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-600
                    @else bg-green-100 text-green-600 @endif">
                    @if($order->status === 'pending') ⏳ Pending
                    @elseif($order->status === 'confirmed') 👨‍🍳 Diproses
                    @else ✅ Siap Diantar @endif
                </span>
            </div>

            {{-- Items --}}
            <div class="bg-gray-50 rounded-xl p-3 mb-4">
                <p class="text-xs text-gray-400 mb-2 font-semibold">DETAIL PESANAN</p>
                <div class="space-y-1">
                    @foreach($order->items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700">{{ $item->product->name }} <span class="text-gray-400">×{{ $item->qty }}</span></span>
                        <span class="font-semibold text-gray-800">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-between font-bold text-[#7A6247] pt-2 mt-2 border-t border-gray-200">
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
                        <button class="w-full bg-blue-500 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-600 transition">
                            ✅ Konfirmasi & Proses
                        </button>
                    </form>
                    <form action="{{ route('kasir.orders.confirm', $order) }}" method="POST" class="w-24">
                        @csrf
                        <input type="hidden" name="status" value="done">
                        <button class="w-full bg-red-100 text-red-500 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-200 transition">
                            ❌ Tolak
                        </button>
                    </form>

                @elseif($order->status === 'confirmed')
                    <form action="{{ route('kasir.orders.confirm', $order) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="status" value="ready">
                        <button class="w-full bg-green-500 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-green-600 transition">
                            🍽️ Siap Diantar
                        </button>
                    </form>

                @elseif($order->status === 'ready')
                    <form action="{{ route('kasir.orders.confirm', $order) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="status" value="done">
                        <button class="w-full bg-gray-500 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-600 transition">
                            🎉 Selesai
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- Auto refresh setiap 15 detik --}}
<script>
    setTimeout(() => location.reload(), 15000);

    function filterStatus(status, btn) {
        document.querySelectorAll('.order-card').forEach(card => {
            card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
        });
        document.querySelectorAll('.status-btn').forEach(b => {
            b.classList.remove('bg-[#7A6247]', 'text-white');
            b.classList.add('bg-white', 'text-gray-600');
        });
        btn.classList.add('bg-[#7A6247]', 'text-white');
        btn.classList.remove('bg-white', 'text-gray-600');
    }
</script>

@endsection