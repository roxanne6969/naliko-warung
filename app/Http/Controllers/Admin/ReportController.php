<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $totalRevenue = Transaction::sum('total');
        $totalTransactions = Transaction::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();

        $recentTransactions = Transaction::with('user', 'items.product')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue', 'totalTransactions',
            'totalProducts', 'totalUsers',
            'recentTransactions'
        ));
    }

    public function orders()
    {
        $orders = Order::with('items.product')
            ->latest()
            ->get();
        return view('admin.orders.index', compact('orders'));
    }
}