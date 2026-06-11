@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Kasir')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-clipboard-document-list', 'w-6 h-6 text-[#5C4A35]')
        <h2 class="text-2xl font-bold text-[#3E2F1E]">Riwayat Pesanan</h2>
    </div>
    <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-1 text-[#9e8065] hover:text-[#5C4A35] text-sm transition">
        @svg('heroicon-o-arrow-left', 'w-4 h-4') Kembali
    </a>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                @svg('heroicon-o-magnifying-glass', 'w-4 h-4 text-[#9e8065]')
            </div>
            <input type="text" id="search" placeholder="Cari nama kasir..."
                oninput="filterTable()"
                class="w-full border border-[#e8d5c1] rounded-xl pl-9 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
        </div>
        <input type="date" id="filter-date" onchange="filterTable()"
            class="border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
        <select id="filter-metode" onchange="filterTable()"
            class="border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
            <option value="">Semua Metode</option>
            <option value="Cash">Cash</option>
            <option value="QRIS">QRIS</option>
        </select>
    </div>
</div>

{{-- Summary --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-4 text-center">
        <p class="text-[#9e8065] text-xs mb-1">Total Transaksi</p>
        <p class="font-bold text-2xl text-[#3E2F1E]">{{ $transactions->total() }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-4 text-center">
        <p class="text-[#9e8065] text-xs mb-1">Total Pendapatan</p>
        <p class="font-bold text-lg text-[#5C4A35]">Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-4 text-center">
        <p class="text-[#9e8065] text-xs mb-1">Transaksi Hari Ini</p>
        <p class="font-bold text-2xl text-green-600">{{ \App\Models\Transaction::whereDate('created_at', today())->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-4 text-center">
        <p class="text-[#9e8065] text-xs mb-1">Pendapatan Hari Ini</p>
        <p class="font-bold text-lg text-green-600">Rp {{ number_format(\App\Models\Transaction::whereDate('created_at', today())->sum('total'), 0, ',', '.') }}</p>
    </div>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] overflow-hidden">
    <table class="w-full" id="trx-table">
        <thead class="bg-[#f5e6d3]">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">#</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Kasir</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Item</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Total</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Bayar</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Kembalian</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Metode</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Waktu</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-[#5C4A35]">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e0cc]" id="trx-body">
            @forelse($transactions as $trx)
            <tr class="hover:bg-[#fdf5ec] trx-row transition"
                data-kasir="{{ strtolower($trx->user->name) }}"
                data-date="{{ $trx->created_at->format('Y-m-d') }}"
                data-metode="{{ $trx->metode ?? 'Cash' }}">
                <td class="px-4 py-3 text-sm text-[#9e8065]">#{{ $trx->id }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-[#3E2F1E]">{{ $trx->user->name }}</td>
                <td class="px-4 py-3 text-sm text-[#5C4A35]">{{ $trx->items->sum('qty') }} item</td>
                <td class="px-4 py-3 text-sm font-bold text-[#5C4A35]">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                <td class="px-4 py-3 text-sm text-[#5C4A35]">Rp {{ number_format($trx->paid, 0, ',', '.') }}</td>
                <td class="px-4 py-3 text-sm text-[#5C4A35]">Rp {{ number_format($trx->change, 0, ',', '.') }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        @if(($trx->metode ?? 'Cash') === 'Cash') bg-green-100 text-green-700
                        @elseif(($trx->metode ?? '') === 'QRIS') bg-blue-100 text-blue-700
                        @else bg-[#f0e0cc] text-[#5C4A35] @endif">
                        {{ $trx->metode ?? 'Cash' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-[#9e8065]">
                    <p>{{ $trx->created_at->format('d/m/Y') }}</p>
                    <p class="text-xs">{{ $trx->created_at->format('H:i') }}</p>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-1">
                        <button onclick="lihatDetail({{ $trx->id }})"
                            class="flex items-center gap-1 text-xs bg-[#f5e6d3] text-[#5C4A35] px-2.5 py-1.5 rounded-lg hover:bg-[#e8d5c1] transition">
                            @svg('heroicon-o-eye', 'w-3.5 h-3.5') Detail
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-4 py-10 text-center text-[#9e8065]">
                    <div class="w-12 h-12 bg-[#f5e6d3] rounded-full flex items-center justify-center mx-auto mb-2">
                        @svg('heroicon-o-clipboard-document-list', 'w-6 h-6 text-[#5C4A35]')
                    </div>
                    <p>Belum ada transaksi</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-[#f0e0cc]">
        {{ $transactions->links() }}
    </div>
</div>

{{-- Modal Detail --}}
<div id="detail-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-xl overflow-hidden border border-[#e8d5c1]">
        <div class="bg-[#5C4A35] px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                @svg('heroicon-o-document-text', 'w-5 h-5 text-[#F7E6CC]')
                <h3 class="text-[#F7E6CC] font-bold text-lg">Detail Transaksi</h3>
            </div>
            <button onclick="closeDetail()" class="text-[#F7E6CC] hover:text-[#dbc7a9]">
                @svg('heroicon-o-x-mark', 'w-5 h-5')
            </button>
        </div>
        <div class="p-6" id="detail-content">
            <p class="text-center text-[#9e8065]">Memuat...</p>
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
                <span class="text-[#9e8065]">ID Transaksi</span>
                <span class="font-semibold text-[#3E2F1E]">#${trx.id}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-[#9e8065]">Waktu</span>
                <span class="font-semibold text-[#3E2F1E]">${new Date(trx.created_at).toLocaleString('id-ID')}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-[#9e8065]">Metode</span>
                <span class="font-semibold text-[#3E2F1E]">${trx.metode ?? 'Cash'}</span>
            </div>
            <hr class="border-[#f0e0cc]">
            <div class="space-y-2">
                ${trx.items.map(item => `
                    <div class="flex justify-between text-sm">
                        <span class="text-[#5C4A35]">${item.product?.name ?? 'Produk'} × ${item.qty}</span>
                        <span class="font-semibold text-[#3E2F1E]">Rp ${fmt(item.price * item.qty)}</span>
                    </div>
                `).join('')}
            </div>
            <hr class="border-[#f0e0cc]">
            <div class="flex justify-between font-bold text-[#5C4A35]">
                <span>Total</span><span>Rp ${fmt(trx.total)}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-[#9e8065]">Bayar</span><span class="text-[#3E2F1E]">Rp ${fmt(trx.paid)}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-[#9e8065]">Kembalian</span><span class="text-green-600 font-semibold">Rp ${fmt(trx.change)}</span>
            </div>
        </div>
        <button onclick="closeDetail()"
             class="w-full mt-4 bg-[#5C4A35] text-[#F7E6CC] py-2.5 rounded-xl hover:bg-[#3E2F1E] transition font-semibold text-sm">
            Tutup
        </button>
    `;
    document.getElementById('detail-modal').classList.remove('hidden');
}

function closeDetail() { document.getElementById('detail-modal').classList.add('hidden'); }
function fmt(num) { return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
document.addEventListener('click', function(e) {
    if (e.target === document.getElementById('detail-modal')) closeDetail();
});
</script>

@endsection