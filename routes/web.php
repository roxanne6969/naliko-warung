<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\AdminLogin;
use App\Livewire\Dashboard;
use App\Livewire\Stock;
use App\Livewire\Admin\Categories\Index as CategoriesIndex;


Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin Login Routes
Route::get('/admin-login', AdminLogin::class)->name('admin.login')->middleware('guest');

// Protected Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/stok-harga', Stock::class)->name('stok-harga');
    Route::get('/kategori', CategoriesIndex::class)->name('kategori');
});

require __DIR__.'/settings.php';
