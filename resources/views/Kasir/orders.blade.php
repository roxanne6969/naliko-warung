@extends('layouts.app')

@section('title', 'Pesanan Masuk - Kasir')

@section('content')

<div style="background-color: #FFE0C2;" class="min-h-screen p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">🔔 Pesanan Masuk</h2>
        <a href="{{ route('kasir.dashboard') }}" style="background-color: #6B4423;" class="text-white px-4 py-2 rounded-lg text-sm hover:opacity-90 transition-opacity font-medium">← Kembali</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
    @endif

    @if($orders->isEmpty())
        <div style="background-color: #FFE0C2;" class="rounded-xl p-10 text-center text-gray-600">
            <p class="text-4xl mb-3">🎉</p>
            <p>Tidak ada pesanan masuk saat ini</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($orders as $order)
            <button 
                onclick="openOrderDetail({{ $order->id }})" 
                style="background-color: #6B4423;" 
                class="rounded-xl shadow-lg p-6 text-left hover:shadow-xl transition-shadow cursor-pointer text-white text-sm">
                <h3 class="font-bold text-lg mb-2">{{ $order->customer_name }}</h3>
                <p class="text-white/80 text-xs mb-3">
                    {{ $order->table_number ?? 'Tanpa meja' }} • 
                    {{ $order->created_at->diffForHumans() }}
                </p>
                <div class="flex justify-between items-center">
                    <span class="text-white/70">Lihat Detail</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-700
                        @else bg-green-100 text-green-700 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </button>
            @endforeach
        </div>
    @endif
</div>

{{-- Modal Detail Pesanan --}}
<div id="orderModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div style="background-color: #6B4423;" class="rounded-xl max-w-2xl w-full max-h-screen overflow-y-auto p-6 text-white">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 id="modalCustomerName" class="text-2xl font-bold"></h2>
                <p id="modalOrderInfo" class="text-white/80 text-sm mt-1"></p>
            </div>
            <button onclick="closeOrderDetail()" class="text-white/70 hover:text-white text-2xl">×</button>
        </div>

        {{-- Items --}}
        <div class="border-t border-white/30 pt-4 mb-4">
            <h3 class="font-bold mb-3">Menu</h3>
            <div id="modalItems" class="space-y-2 text-sm"></div>
            <div class="border-t border-white/30 mt-3 pt-3 flex justify-between font-bold text-lg">
                <span>Total</span>
                <span id="modalTotal"></span>
            </div>
        </div>

        {{-- Catatan --}}
        <div id="noteSection" class="border-t border-white/30 pt-4 mb-4 hidden">
            <p class="text-white/80">📝 <span id="modalNote"></span></p>
        </div>

        {{-- Aksi --}}
        <div id="modalActions" class="flex gap-2 mt-4"></div>
    </div>
</div>

{{-- Data Orders untuk JavaScript --}}
<script>
const orderItems = {!! json_encode($orders->map(function($order) {
    return [
        'id' => $order->id,
        'customer_name' => $order->customer_name,
        'table_number' => $order->table_number,
        'status' => $order->status,
        'note' => $order->note,
        'created_at' => $order->created_at->diffForHumans(),
        'total' => number_format($order->total, 0, ',', '.'),
        'items' => $order->items->map(function($item) {
            return [
                'name' => $item->product->name,
                'qty' => $item->qty,
                'price' => number_format($item->price * $item->qty, 0, ',', '.')
            ];
        })->toArray()
    ];
})->toArray()) !!};

function openOrderDetail(orderId) {
    const order = orderItems.find(o => o.id === orderId);
    if (!order) return;

    // Update modal content
    document.getElementById('modalCustomerName').textContent = order.customer_name;
    document.getElementById('modalOrderInfo').textContent = 
        (order.table_number || 'Tanpa meja') + ' • ' + order.created_at;
    
    // Items
    const itemsHtml = order.items.map(item => 
        `<div class="flex justify-between">
            <span>${item.name} x${item.qty}</span>
            <span>Rp ${item.price}</span>
        </div>`
    ).join('');
    document.getElementById('modalItems').innerHTML = itemsHtml;
    document.getElementById('modalTotal').textContent = 'Rp ' + order.total;

    // Catatan
    if (order.note) {
        document.getElementById('noteSection').classList.remove('hidden');
        document.getElementById('modalNote').textContent = order.note;
    } else {
        document.getElementById('noteSection').classList.add('hidden');
    }

    // Aksi
    const actionsHtml = getActionButtons(orderId, order.status);
    document.getElementById('modalActions').innerHTML = actionsHtml;

    // Buka modal
    document.getElementById('orderModal').classList.remove('hidden');
}

function closeOrderDetail() {
    document.getElementById('orderModal').classList.add('hidden');
}

function getActionButtons(orderId, status) {
    const route = '{{ route("kasir.orders.confirm", ":id") }}'.replace(':id', orderId);
    let html = '';

    if (status === 'pending') {
        html = `
            <form action="${route}" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="status" value="confirmed">
                <button style="background-color: #FF8C42;" class="w-full text-white py-2 rounded-lg text-sm hover:opacity-90 transition-opacity">
                    ✅ Konfirmasi
                </button>
            </form>
        `;
    } else if (status === 'confirmed') {
        html = `
            <form action="${route}" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="status" value="ready">
                <button style="background-color: #FFB84D;" class="w-full text-white py-2 rounded-lg text-sm hover:opacity-90 transition-opacity">
                    🍽️ Siap Diantar
                </button>
            </form>
        `;
    } else if (status === 'ready') {
        html = `
            <form action="${route}" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="status" value="done">
                <button style="background-color: #A0826D;" class="w-full text-white py-2 rounded-lg text-sm hover:opacity-90 transition-opacity">
                    🎉 Selesai
                </button>
            </form>
        `;
    }

    return html;
}

// Tutup modal saat klik di luar
document.getElementById('orderModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'orderModal') closeOrderDetail();
});
</script>

{{-- Auto refresh --}}
<script>setTimeout(() => location.reload(), 15000);</script>

@endsection