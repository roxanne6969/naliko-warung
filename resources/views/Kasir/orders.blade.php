@extends('layouts.app')

@section('title', 'Pesanan Online - Kasir')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-bell', 'w-6 h-6 text-[#5C4A35]')
        <div>
            <h2 class="text-2xl font-bold text-[#3E2F1E]">Pesanan Online</h2>
            <p class="text-[#9e8065] text-sm">Terima & proses pesanan yang sudah bayar</p>
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

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-4 text-center">
        <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center mx-auto mb-2">
            @svg('heroicon-o-clock', 'w-5 h-5 text-yellow-500')
        </div>
        <p class="text-[#9e8065] text-xs">Pending</p>
        <p class="font-bold text-2xl text-yellow-500">{{ $orders->where('status', 'pending')->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-4 text-center">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-2">
            @svg('heroicon-o-fire', 'w-5 h-5 text-blue-500')
        </div>
        <p class="text-[#9e8065] text-xs">Diproses</p>
        <p class="font-bold text-2xl text-blue-500">{{ $orders->where('status', 'confirmed')->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-4 text-center">
        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mx-auto mb-2">
            @svg('heroicon-o-check-badge', 'w-5 h-5 text-green-600')
        </div>
        <p class="text-[#9e8065] text-xs">Siap Diantar</p>
        <p class="font-bold text-2xl text-green-600">{{ $orders->where('status', 'ready')->count() }}</p>
    </div>
</div>

@if($orders->isEmpty())
    <div class="bg-white rounded-2xl border border-[#e8d5c1] p-10 text-center shadow-sm">
        <div class="w-16 h-16 bg-[#f5e6d3] rounded-full flex items-center justify-center mx-auto mb-4">
            @svg('heroicon-o-check-circle', 'w-8 h-8 text-[#5C4A35]')
        </div>
        <p class="font-semibold text-[#3E2F1E]">Tidak ada pesanan aktif saat ini</p>
        <p class="text-[#9e8065] text-sm mt-1">Pesanan baru akan muncul di sini secara otomatis</p>
    </div>
@else
    {{-- Filter Tab --}}
    <div class="flex gap-2 mb-4 flex-wrap">
        <button onclick="filterStatus('all', this)"
            class="status-btn px-4 py-1.5 rounded-full bg-[#5C4A35] text-[#F7E6CC] text-sm font-semibold">
            Semua
        </button>
        <button onclick="filterStatus('pending', this)"
            class="status-btn px-4 py-1.5 rounded-full bg-white text-[#9e8065] text-sm border border-[#e8d5c1]">
            Pending
        </button>
        <button onclick="filterStatus('confirmed', this)"
            class="status-btn px-4 py-1.5 rounded-full bg-white text-[#9e8065] text-sm border border-[#e8d5c1]">
            Diproses
        </button>
        <button onclick="filterStatus('ready', this)"
            class="status-btn px-4 py-1.5 rounded-full bg-white text-[#9e8065] text-sm border border-[#e8d5c1]">
            Siap Diantar
        </button>
    </div>

    <div class="space-y-4" id="orders-container">
        @foreach($orders as $order)
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 order-card" data-status="{{ $order->status }}">

            {{-- Header --}}
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        @svg('heroicon-o-user', 'w-4 h-4 text-[#9e8065]')
                        <h3 class="font-bold text-[#3E2F1E] text-lg">{{ $order->customer_name }}</h3>
                        <span class="text-[#9e8065] text-sm">#{{ $order->id }}</span>
                    </div>
                    <div class="flex gap-3 text-[#9e8065] text-xs mt-1 ml-6">
                        <span>{{ $order->table_number ?? 'Tanpa meja' }}</span>
                        <span>{{ $order->created_at->diffForHumans() }}</span>
                        @if(isset($order->payment_method))
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full font-semibold">
                                {{ $order->payment_method }}
                            </span>
                        @endif
                    </div>
                    @if($order->note)
                        <p class="text-[#5C4A35] text-sm mt-1 ml-6">{{ $order->note }}</p>
                    @endif
                </div>
                <div class="text-right space-y-1">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold block
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-700
                        @else bg-green-100 text-green-700 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold block
                        @if($order->payment_status === 'paid') bg-green-100 text-green-700
                        @else bg-yellow-100 text-yellow-700 @endif">
                        {{ $order->payment_status === 'paid' ? '✅ Lunas' : '⏳ Belum Bayar' }}
                    </span>
                </div>
            </div>

            {{-- Items --}}
            <div class="bg-[#fdfaf7] rounded-xl border border-[#f0e0cc] p-3 mb-4">
                <p class="text-xs text-[#9e8065] mb-2 font-semibold uppercase tracking-wide">Detail Pesanan</p>
                <div class="space-y-1">
                    @foreach($order->items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-[#3E2F1E]">{{ $item->product->name }} <span class="text-[#9e8065]">×{{ $item->qty }}</span></span>
                        <span class="font-semibold text-[#5C4A35]">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-between font-bold text-[#5C4A35] pt-2 mt-2 border-t border-[#f0e0cc]">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Bukti Pembayaran --}}
            @if($order->payment_proof)
            <div class="mb-4">
                <p class="text-xs text-[#9e8065] mb-2 font-semibold uppercase tracking-wide">Bukti Pembayaran</p>
                <div class="border border-[#e8d5c1] rounded-xl overflow-hidden">
                    <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                        alt="Bukti Pembayaran"
                        class="w-full max-h-48 object-contain cursor-pointer bg-[#fdfaf7]"
                        onclick="lihatBukti('{{ asset('storage/' . $order->payment_proof) }}')">
                    <p class="text-xs text-center text-[#9e8065] py-1">Klik gambar untuk memperbesar</p>
                </div>
            </div>
            @endif

            {{-- Aksi --}}
            <div class="flex gap-2">
                @if($order->status === 'pending')
                    <form action="{{ route('kasir.orders.confirm', $order) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="status" value="confirmed">
                        <button class="w-full flex items-center justify-center gap-2 bg-blue-500 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-600 transition">
                            @svg('heroicon-o-check', 'w-4 h-4') Konfirmasi & Proses
                        </button>
                    </form>
                    <form action="{{ route('kasir.orders.confirm', $order) }}" method="POST" class="w-24">
                        @csrf
                        <input type="hidden" name="status" value="done">
                        <button class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-500 border border-red-200 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-100 transition">
                            @svg('heroicon-o-x-mark', 'w-4 h-4') Tolak
                        </button>
                    </form>

                @elseif($order->status === 'confirmed')
                    <form action="{{ route('kasir.orders.confirm', $order) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="status" value="ready">
                        <button class="w-full flex items-center justify-center gap-2 bg-green-500 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-green-600 transition">
                            @svg('heroicon-o-check-badge', 'w-4 h-4') Siap Diantar
                        </button>
                    </form>

                @elseif($order->status === 'ready')
                    <form action="{{ route('kasir.orders.confirm', $order) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="status" value="done">
                        <button class="w-full flex items-center justify-center gap-2 bg-[#5C4A35] text-[#F7E6CC] py-2.5 rounded-xl text-sm font-semibold hover:bg-[#3E2F1E] transition">
                            @svg('heroicon-o-flag', 'w-4 h-4') Selesai
                        </button>
                    </form>
                @endif
            </div>

        </div>
        @endforeach
    </div>
@endif

{{-- Modal Lihat Bukti --}}
<div id="bukti-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4"
    onclick="closeBukti()">
    <div class="relative max-w-lg w-full">
        <button onclick="closeBukti()" 
            class="absolute -top-10 right-0 text-white text-xl hover:text-gray-300">✕ Tutup</button>
        <img id="bukti-img" src="" alt="Bukti Pembayaran" class="w-full rounded-2xl shadow-xl">
    </div>
</div>

{{-- Auto refresh setiap 15 detik --}}
<script>
    setTimeout(() => location.reload(), 15000);

    function filterStatus(status, btn) {
        document.querySelectorAll('.order-card').forEach(card => {
            card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
        });
        document.querySelectorAll('.status-btn').forEach(b => {
            b.classList.remove('bg-[#5C4A35]', 'text-[#F7E6CC]');
            b.classList.add('bg-white', 'text-[#9e8065]', 'border', 'border-[#e8d5c1]');
        });
        btn.classList.add('bg-[#5C4A35]', 'text-[#F7E6CC]');
        btn.classList.remove('bg-white', 'text-[#9e8065]', 'border', 'border-[#e8d5c1]');
    }

    function lihatBukti(url) {
        document.getElementById('bukti-img').src = url;
        document.getElementById('bukti-modal').classList.remove('hidden');
    }

    function closeBukti() {
        document.getElementById('bukti-modal').classList.add('hidden');
    }
</script>

@endsection