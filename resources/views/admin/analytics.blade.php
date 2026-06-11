@extends('layouts.app')

@section('title', 'Admin Analytics')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-chart-pie', 'w-7 h-7 text-[#5C4A35]')
        <div>
            <h2 class="text-2xl font-bold text-[#3E2F1E]">Analytics Dashboard</h2>
            <p class="text-[#9e8065] text-sm">Monitor business performance</p>
        </div>
    </div>
</div>

{{-- Filter Form --}}
<div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 mb-8">
    <form action="{{ route('admin.analytics') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/4">
            <label class="block text-sm font-semibold text-[#5C4A35] mb-2">Periode</label>
            <select name="period" id="period-select" onchange="toggleCustomDates()" class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
                <option value="">Semua Waktu (Default)</option>
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="this_week" {{ $period == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Kustom</option>
            </select>
        </div>
        <div class="w-full md:w-1/4 custom-date {{ $period == 'custom' ? '' : 'hidden' }}">
            <label class="block text-sm font-semibold text-[#5C4A35] mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
        </div>
        <div class="w-full md:w-1/4 custom-date {{ $period == 'custom' ? '' : 'hidden' }}">
            <label class="block text-sm font-semibold text-[#5C4A35] mb-2">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
        </div>
        <div>
            <button type="submit" class="bg-[#5C4A35] text-[#F7E6CC] px-6 py-2.5 rounded-xl font-semibold hover:bg-[#3E2F1E] transition">
                Terapkan
            </button>
            @if($hasFilter)
                <a href="{{ route('admin.analytics') }}" class="ml-2 px-6 py-2.5 rounded-xl text-[#5C4A35] hover:bg-[#f5e6d3] transition">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Top Metrics --}}
@if($hasFilter)
    <div class="mb-6">
        <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
            @svg('heroicon-o-funnel', 'w-4 h-4') Filter Aktif: {{ $filterLabel }}
        </span>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
            <p class="text-[#9e8065] text-sm mb-1">Total Pendapatan (Filter)</p>
            <p class="font-bold text-2xl text-[#5C4A35]">Rp {{ number_format($filteredRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
            <p class="text-[#9e8065] text-sm mb-1">Total Transaksi (Filter)</p>
            <p class="font-bold text-2xl text-[#3E2F1E]">{{ number_format($filteredTransactions) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
            <p class="text-[#9e8065] text-sm mb-1">Rata-rata Transaksi (Filter)</p>
            <p class="font-bold text-2xl text-[#3E2F1E]">Rp {{ number_format($filteredAvgTransaction, 0, ',', '.') }}</p>
        </div>
    </div>
@else
    <h3 class="text-sm font-semibold text-[#9e8065] uppercase tracking-wider mb-4">Ringkasan Saat Ini</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
            <p class="text-[#9e8065] text-xs mb-1">Pendapatan Hari Ini</p>
            <p class="font-bold text-lg text-green-600">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
            <p class="text-[#9e8065] text-xs mb-1">Pendapatan Minggu Ini</p>
            <p class="font-bold text-lg text-[#5C4A35]">Rp {{ number_format($weekRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
            <p class="text-[#9e8065] text-xs mb-1">Pendapatan Bulan Ini</p>
            <p class="font-bold text-lg text-[#5C4A35]">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
            <p class="text-[#9e8065] text-xs mb-1">Transaksi Hari Ini</p>
            <p class="font-bold text-lg text-[#3E2F1E]">{{ number_format($todayTransactions) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
            <p class="text-[#9e8065] text-xs mb-1">Transaksi Minggu Ini</p>
            <p class="font-bold text-lg text-[#3E2F1E]">{{ number_format($weekTransactions) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 text-center">
            <p class="text-[#9e8065] text-xs mb-1">Transaksi Bulan Ini</p>
            <p class="font-bold text-lg text-[#3E2F1E]">{{ number_format($monthTransactions) }}</p>
        </div>
    </div>
@endif

{{-- Analytics Content --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left Column: Trend & Cashier --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Sales Trend --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5">
            <h3 class="font-bold text-[#3E2F1E] mb-4 flex items-center gap-2">
                @svg('heroicon-o-chart-bar', 'w-5 h-5 text-[#5C4A35]') Tren Penjualan
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[#9e8065] border-b border-[#f0e0cc]">
                            <th class="py-2 px-3">Tanggal</th>
                            <th class="py-2 px-3 text-right">Transaksi</th>
                            <th class="py-2 px-3 text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesTrend as $trend)
                        <tr class="border-b border-[#f0e0cc] last:border-0 hover:bg-[#fdf5ec]">
                            <td class="py-2 px-3 font-semibold text-[#3E2F1E]">{{ \Carbon\Carbon::parse($trend->date)->format('d M Y') }}</td>
                            <td class="py-2 px-3 text-right">{{ $trend->total_transactions }}</td>
                            <td class="py-2 px-3 text-right font-bold text-[#5C4A35]">Rp {{ number_format($trend->revenue, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-[#9e8065]">Belum ada data trend</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Cashier Activity --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5">
            <h3 class="font-bold text-[#3E2F1E] mb-4 flex items-center gap-2">
                @svg('heroicon-o-users', 'w-5 h-5 text-[#5C4A35]') Aktivitas Kasir
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[#9e8065] border-b border-[#f0e0cc]">
                            <th class="py-2 px-3">ID</th>
                            <th class="py-2 px-3">Kasir</th>
                            <th class="py-2 px-3">Metode</th>
                            <th class="py-2 px-3 text-center">Item</th>
                            <th class="py-2 px-3 text-right">Total</th>
                            <th class="py-2 px-3 text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cashierActivity as $activity)
                        <tr class="border-b border-[#f0e0cc] last:border-0 hover:bg-[#fdf5ec]">
                            <td class="py-2 px-3 text-[#9e8065]">#{{ $activity->id }}</td>
                            <td class="py-2 px-3 font-semibold text-[#3E2F1E]">{{ $activity->user->name }}</td>
                            <td class="py-2 px-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                    @if(($activity->metode ?? 'Cash') === 'Cash') bg-green-100 text-green-700
                                    @elseif(($activity->metode ?? '') === 'QRIS') bg-blue-100 text-blue-700
                                    @else bg-[#f0e0cc] text-[#5C4A35] @endif">
                                    {{ $activity->metode ?? 'Cash' }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-center">{{ $activity->items->sum('qty') }}</td>
                            <td class="py-2 px-3 text-right font-bold text-[#5C4A35]">Rp {{ number_format($activity->total, 0, ',', '.') }}</td>
                            <td class="py-2 px-3 text-right text-xs text-[#9e8065]">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-[#9e8065]">Belum ada aktivitas kasir</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right Column: Best Selling --}}
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 sticky top-24">
            <h3 class="font-bold text-[#3E2F1E] mb-4 flex items-center gap-2">
                @svg('heroicon-o-fire', 'w-5 h-5 text-red-500') Menu Terlaris
            </h3>
            
            <div class="space-y-4">
                @forelse($bestSellingProducts as $item)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-[#f0e0cc] hover:bg-[#fdf5ec] transition">
                    <div class="w-8 h-8 rounded-full bg-[#f5e6d3] flex items-center justify-center font-bold text-[#5C4A35] text-sm shrink-0">
                        {{ $loop->iteration }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-[#3E2F1E] truncate">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                        <p class="text-[#9e8065] text-xs">Terjual: {{ $item->total_qty }} porsi</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-[#5C4A35] text-sm">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-[#9e8065]">
                    @svg('heroicon-o-archive-box-x-mark', 'w-8 h-8 mx-auto mb-2 text-[#e8d5c1]')
                    <p>Belum ada data penjualan</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

<script>
function toggleCustomDates() {
    const val = document.getElementById('period-select').value;
    const dates = document.querySelectorAll('.custom-date');
    if (val === 'custom') {
        dates.forEach(el => el.classList.remove('hidden'));
    } else {
        dates.forEach(el => el.classList.add('hidden'));
    }
}
</script>

@endsection
