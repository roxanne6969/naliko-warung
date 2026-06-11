<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // 1. Static Metrics
        $todayRevenue = Transaction::whereDate('created_at', Carbon::today())->sum('total');
        $weekRevenue = Transaction::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
        $monthRevenue = Transaction::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('total');

        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())->count();
        $weekTransactions = Transaction::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $monthTransactions = Transaction::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();

        // 2. Filter Logic
        $period = $request->get('period');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $hasFilter = false;
        $filterLabel = '';
        $queryStartDate = null;
        $queryEndDate = null;

        if ($period == 'today') {
            $hasFilter = true;
            $filterLabel = 'Hari Ini';
            $queryStartDate = Carbon::today()->startOfDay();
            $queryEndDate = Carbon::today()->endOfDay();
        } elseif ($period == 'this_week') {
            $hasFilter = true;
            $filterLabel = 'Minggu Ini';
            $queryStartDate = Carbon::now()->startOfWeek();
            $queryEndDate = Carbon::now()->endOfWeek();
        } elseif ($period == 'this_month') {
            $hasFilter = true;
            $filterLabel = 'Bulan Ini';
            $queryStartDate = Carbon::now()->startOfMonth();
            $queryEndDate = Carbon::now()->endOfMonth();
        } elseif ($period == 'custom' && $startDate && $endDate) {
            $hasFilter = true;
            $queryStartDate = Carbon::parse($startDate)->startOfDay();
            $queryEndDate = Carbon::parse($endDate)->endOfDay();
            $filterLabel = $queryStartDate->format('d M Y') . ' - ' . $queryEndDate->format('d M Y');
        }

        // 3. Filtered Metrics (if filter active)
        $filteredRevenue = 0;
        $filteredTransactions = 0;
        $filteredAvgTransaction = 0;

        if ($hasFilter) {
            $filteredRevenue = Transaction::whereBetween('created_at', [$queryStartDate, $queryEndDate])->sum('total');
            $filteredTransactions = Transaction::whereBetween('created_at', [$queryStartDate, $queryEndDate])->count();
            $filteredAvgTransaction = $filteredTransactions > 0 ? $filteredRevenue / $filteredTransactions : 0;
        }

        // 4. Best Selling Products (Top 5)
        $bestSellingQuery = TransactionItem::with('product')
            ->selectRaw('product_id, SUM(qty) as total_qty, SUM(qty * price) as total_revenue');
            
        if ($hasFilter) {
            $bestSellingQuery->whereBetween('created_at', [$queryStartDate, $queryEndDate]);
        }
        
        $bestSellingProducts = $bestSellingQuery->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // 5. Sales Trend
        // If filter is active, group by date within filter range. If not, default to last 7 days.
        $trendQuery = Transaction::selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as total_transactions');
        if ($hasFilter) {
            $trendQuery->whereBetween('created_at', [$queryStartDate, $queryEndDate]);
        } else {
            $trendQuery->whereDate('created_at', '>=', Carbon::now()->subDays(6));
        }
        
        $salesTrend = $trendQuery->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        // 6. Cashier Activity
        $activityQuery = Transaction::with(['user', 'items']);
        if ($hasFilter) {
            $activityQuery->whereBetween('created_at', [$queryStartDate, $queryEndDate]);
        }
        $cashierActivity = $activityQuery->latest()->take(10)->get();

        return view('admin.analytics', compact(
            'todayRevenue', 'weekRevenue', 'monthRevenue',
            'todayTransactions', 'weekTransactions', 'monthTransactions',
            'hasFilter', 'filterLabel', 'period', 'startDate', 'endDate',
            'filteredRevenue', 'filteredTransactions', 'filteredAvgTransaction',
            'bestSellingProducts', 'salesTrend', 'cashierActivity'
        ));
    }
}
