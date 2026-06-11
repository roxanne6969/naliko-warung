@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Kasir')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">📋 Riwayat Pesanan</h2>
    <a href="{{ route('kasir.dashboard') }}" class="text-gray-400 hover:text-[#7A6247] text-sm">← Kembali</a>
</div>

{{-- Filter & Search --}}
<div class="bg-white rounded-2xl shadow p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <input type="text" id="search" placeholder="🔍 Cari nama kasir..."
            oninput="filterTable()"
            class="border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#7A6247]">
        <input type="date" id="filter-date" onchange="filterTable()"
            class="border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#7A6247]">
        <select id="filter-metode" onchange="filterTable()"
            class="border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#7A6247]">
            <option value="">Semua Metode</option>
            <option value="Cash">Cash</option>
            <option value="QRIS">QRIS</option>
            <option value="Debit">Debit</option>
        </select>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-gray-400 text-xs mb-1">Total Transaksi</p>
        <p class="font-bold text-2xl text-gray-800">{{ $transactions->total() }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-gray-400 text-xs mb-1">Total Pendapatan</p>
        <p class="font-bold text-lg text-[#7A6247]">Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-gray-400 text-xs mb-1">Transaksi Hari Ini</p>
        <p class="font-bold text-2xl text-green-600">{{ \App\Models\Transaction::whereDate('created_at', today())->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-gray-400 text-xs mb-1">Pendapatan Hari Ini</p>
        <p class="font-bold text-lg text-green-600">Rp {{ number_format(\App\Models\Transaction::whereDate('created_at', today())->sum('total'), 0, ',', '.') }}</p>
    </div>
</div>

{{-- Tabel Transaksi --}}
<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full" id="trx-table">
        <thead class="bg-[#f5e6d3]">
            <tr>
                <th class="px-4 py-3 text-left text-sm text-[#7A6247]">#</th>
                <th class="px-4 py-3 text-left text-sm text-[#7A6247]">Kasir</th>
                <th class="px-4 py-3 text-left text-sm text-[#7A6247]">Item</th>
                <th class="px-4 py-3 text-left text-sm text-[#7A6247]">Total</th>
                <th class="px-4 py-3 text-left text-sm text-[#7A6247]">Bayar</th>
                <th class="px-4 py-3 text-left text-sm text-[#7A6247]">Kembalian</th>
                <th class="px-4 py-3 text-left text-sm text-[#7A6247]">Metode</th>
                <th class="px-4 py-3 text-left text-sm text-[#7A6247]">Waktu</th>
                <th class="px-4 py-3 text-left text-sm text-[#7A6247]">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y" id="trx-body">
            @forelse($transactions as $trx)
            <tr class="hover:bg-[#fdf5ec] trx-row"
                data-kasir="{{ strtolower($trx->user->name) }}"
                data-date="{{ $trx->created_at->format('Y-m-d') }}"
                data-metode="{{ $trx->metode ?? 'Cash' }}">
                <td class="px-4 py-3 text-sm text-gray-400">#{{ $trx->id }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $trx->user->name }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $trx->items->sum('qty') }} item</td>
                <td class="px-4 py-3 text-sm font-bold text-[#7A6247]">
                    Rp {{ number_format($trx->total, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    Rp {{ number_format($trx->paid, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    Rp {{ number_format($trx->change, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        @if(($trx->metode ?? 'Cash') === 'Cash') bg-green-100 text-green-600
                        @elseif(($trx->metode ?? '') === 'QRIS') bg-blue-100 text-blue-600
                        @else bg-purple-100 text-purple-600 @endif">
                        {{ $trx->metode ?? 'Cash' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-400">
                    <p>{{ $trx->created_at->format('d/m/Y') }}</p>
                    <p class="text-xs">{{ $trx->created_at->format('H:i') }}</p>
                </td>
                <td class="px-4 py-3">
                    <button onclick="lihatDetail({{ $trx->id }})"
                        class="text-xs bg-[#f5e6d3] text-[#7A6247] px-3 py-1 rounded-lg hover:bg-[#e8d5c1] mr-1">
                        👁️ Detail
                    </button>
                    <button onclick="cetakStruk({{ $trx->id }})"
                        class="text-xs bg-[#7A6247] text-white px-3 py-1 rounded-lg hover:bg-[#5E4A33]">
                        🖨️ Cetak
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-4 py-10 text-center text-gray-400">
                    <p class="text-4xl mb-2">📋</p>
                    <p>Belum ada transaksi</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">
        {{ $transactions->links() }}
    </div>
</div>

{{-- Modal Detail --}}
<div id="detail-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-xl overflow-hidden">
        <div class="bg-[#7A6247] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-lg">🧾 Detail Transaksi</h3>
            <button onclick="closeDetail()" class="text-white hover:text-[#dbc7a9] text-xl">✕</button>
        </div>
        <div class="p-6" id="detail-content">
            <p class="text-center text-gray-400">Memuat...</p>
        </div>
    </div>
</div>

<script>
const transactions = @json($transactions->items());

function filterTable() {
    const q = document.getElementById('search').value.toLowerCase();
    const date = document.getElementById('filter-date').value;
    const metode = document.getElementById('filter-metode').value;

    document.querySelectorAll('.trx-row').forEach(row => {
        const matchKasir = row.dataset.kasir.includes(q);
        const matchDate = !date || row.dataset.date === date;
        const matchMetode = !metode || row.dataset.metode === metode;
        row.style.display = matchKasir && matchDate && matchMetode ? '' : 'none';
    });
}

function lihatDetail(id) {
    const trx = transactions.find(t => t.id === id);
    if (!trx) return;

    document.getElementById('detail-content').innerHTML = `
        <div class="space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">ID Transaksi</span>
                <span class="font-semibold">#${trx.id}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Waktu</span>
                <span class="font-semibold">${new Date(trx.created_at).toLocaleString('id-ID')}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Metode</span>
                <span class="font-semibold">${trx.metode ?? 'Cash'}</span>
            </div>
            <hr>
            <div class="space-y-2">
                ${trx.items.map(item => `
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700">${item.product?.name ?? 'Produk'} × ${item.qty}</span>
                        <span class="font-semibold">Rp ${fmt(item.price * item.qty)}</span>
                    </div>
                `).join('')}
            </div>
            <hr>
            <div class="flex justify-between font-bold text-[#7A6247]">
                <span>Total</span>
                <span>Rp ${fmt(trx.total)}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Bayar</span>
                <span>Rp ${fmt(trx.paid)}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Kembalian</span>
                <span class="text-green-600 font-semibold">Rp ${fmt(trx.change)}</span>
            </div>
        </div>
        <button onclick="cetakStruk(${trx.id})"
            class="w-full mt-4 bg-[#7A6247] text-white py-2 rounded-xl hover:bg-[#5E4A33]">
            🖨️ Cetak Struk
        </button>
    `;

    document.getElementById('detail-modal').classList.remove('hidden');
}

function closeDetail() {
    document.getElementById('detail-modal').classList.add('hidden');
}

function cetakStruk(id) {
    alert('Fitur cetak struk #' + id + ' coming soon!');
}

function fmt(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

document.addEventListener('click', function(e) {
    if (e.target === document.getElementById('detail-modal')) closeDetail();
});
</script>

@endsection