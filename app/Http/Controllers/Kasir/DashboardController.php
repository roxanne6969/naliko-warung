<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $waitingVerification = Order::where('payment_status', 'waiting_verification')->count();
        $pendingOrders = Order::where('status', 'pending')->where('payment_status', 'paid')->count();
        $processingOrders = Order::whereIn('status', ['confirmed', 'ready'])->where('payment_status', 'paid')->count();
        $todayRevenue = Transaction::whereDate('created_at', today())->sum('total');

        $activeOrders = Order::whereIn('status', ['pending', 'confirmed', 'ready'])
            ->where('payment_status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact(
            'waitingVerification',
            'pendingOrders',
            'processingOrders',
            'todayRevenue',
            'activeOrders'
        ));
    }
}
