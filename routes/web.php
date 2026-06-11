<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\TransactionController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Kasir\PaymentVerifController;


// Public routes (pelanggan)
Route::get('/', [MenuController::class, 'index'])->name('menu');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{order}/status', [OrderController::class, 'status'])->name('order.status');
Route::get('/order/{order}/payment', [PaymentController::class, 'show'])->name('order.payment');
Route::post('/order/{order}/payment', [PaymentController::class, 'upload'])->name('order.payment.upload');

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class);
    Route::get('orders', [ReportController::class, 'orders'])->name('admin.orders');
    Route::get('reports', [ReportController::class, 'index'])->name('admin.reports');
    
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics');
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});

// Kasir routes
Route::prefix('kasir')->middleware(['auth', 'kasir'])->group(function () {
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('kasir.dashboard');

    Route::get('/orders', [TransactionController::class, 'orders'])->name('kasir.orders');
    Route::post('/orders/{order}/confirm', [TransactionController::class, 'confirm'])->name('kasir.orders.confirm');
    Route::get('/transaction', [TransactionController::class, 'index'])->name('kasir.transaction');
    Route::post('/transaction', [TransactionController::class, 'store'])->name('kasir.transaction.store');
    Route::get('/history', [TransactionController::class, 'history'])->name('kasir.history');
    Route::get('/stok', [TransactionController::class, 'stok'])->name('kasir.stok');
    Route::get('/payment', [PaymentVerifController::class, 'index'])->name('kasir.payment.index');
    Route::post('/payment/{order}/verify', [PaymentVerifController::class, 'verify'])->name('kasir.payment.verify');
});