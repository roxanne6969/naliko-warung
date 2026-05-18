<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Kasir\TransactionController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\OrderController;

// Public routes (pelanggan)
Route::get('/', [MenuController::class, 'index'])->name('menu');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{order}/status', [OrderController::class, 'status'])->name('order.status');

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class);
    Route::get('orders', [ReportController::class, 'orders'])->name('admin.orders');
    Route::get('reports', [ReportController::class, 'index'])->name('admin.reports');
});

// Kasir routes
Route::prefix('kasir')->middleware(['auth', 'kasir'])->group(function () {
    Route::get('/dashboard', function () {
        return view('kasir.dashboard');
    })->name('kasir.dashboard');

    Route::get('/orders', [TransactionController::class, 'orders'])->name('kasir.orders');
    Route::post('/orders/{order}/confirm', [TransactionController::class, 'confirm'])->name('kasir.orders.confirm');
    Route::get('/transaction', [TransactionController::class, 'index'])->name('kasir.transaction');
    Route::post('/transaction', [TransactionController::class, 'store'])->name('kasir.transaction.store');
});