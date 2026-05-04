<?php

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;

new class extends Component
{
    public int $totalPesanan = 0;
    public string $totalPendapatan = 'Rp 0';
    public string $menuTerlaris = '-';

    public function mount()
    {
        $this->totalPesanan = Order::hariIni()->count();

        $pendapatan = Order::hariIni()->selesai()->sum('total');
        $this->totalPendapatan = 'Rp ' . number_format($pendapatan, 0, ',', '.');

        $this->menuTerlaris = OrderItem::whereDate('created_at', today())
            ->selectRaw('menu_name, SUM(quantity) as total_qty')
            ->groupBy('menu_name')
            ->orderByDesc('total_qty')
            ->value('menu_name') ?? '-';
    }
};
?>

<div class="nw-dashboard">
    <div class="nw-dashboard__header">
        <h1 class="nw-dashboard__title">INFORMASI HARI INI</h1>
    </div>

    <div class="nw-dashboard__cards">

        <div class="nw-card">
            <div class="nw-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h2 class="nw-card__label">Total Pesanan</h2>
            <div class="nw-card__divider"></div>
            <p class="nw-card__value">{{ $totalPesanan }}</p>
            <p class="nw-card__sub">pesanan masuk hari ini</p>
        </div>

        <div class="nw-card">
            <div class="nw-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="nw-card__label">Total Pendapatan</h2>
            <div class="nw-card__divider"></div>
            <p class="nw-card__value nw-card__value--sm">{{ $totalPendapatan }}</p>
            <p class="nw-card__sub">pendapatan hari ini</p>
        </div>

        <div class="nw-card">
            <div class="nw-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
            <h2 class="nw-card__label">Menu Terlaris</h2>
            <div class="nw-card__divider"></div>
            <p class="nw-card__value nw-card__value--sm">{{ $menuTerlaris }}</p>
            <p class="nw-card__sub">paling banyak dipesan hari ini</p>
        </div>

    </div>
</div>

<style>
    .nw-dashboard { padding: 2rem 2.5rem; font-family: 'Georgia', serif; }
    .nw-dashboard__header { text-align: center; margin-bottom: 2rem; }
    .nw-dashboard__title { font-size: 1.75rem; font-weight: 700; letter-spacing: 0.08em; color: #4a3728; text-transform: uppercase; }
    .nw-dashboard__cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    @media (max-width: 768px) { .nw-dashboard__cards { grid-template-columns: 1fr; } .nw-dashboard { padding: 1.25rem; } }
    .nw-card { background-color: #8b7355; border-radius: 0.75rem; padding: 1.75rem 1.5rem; color: #f5ede0; display: flex; flex-direction: column; align-items: flex-start; gap: 0.5rem; box-shadow: 0 2px 8px rgba(74,55,40,0.18); transition: transform 0.18s ease, box-shadow 0.18s ease; }
    .nw-card:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(74,55,40,0.25); }
    .nw-card__icon { opacity: 0.85; margin-bottom: 0.25rem; }
    .nw-card__label { font-size: 1rem; font-weight: 600; letter-spacing: 0.03em; margin: 0; }
    .nw-card__divider { width: 100%; height: 1px; background-color: rgba(245,237,224,0.35); margin: 0.25rem 0; }
    .nw-card__value { font-size: 2rem; font-weight: 700; color: #fff8f0; margin: 0; line-height: 1.2; }
    .nw-card__value--sm { font-size: 1.35rem; }
    .nw-card__sub { font-size: 0.78rem; color: rgba(245,237,224,0.7); margin: 0; font-style: italic; }
</style>