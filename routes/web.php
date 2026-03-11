<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;


 //Trang chủ 
Route::get('/', [HomeController::class, 'index'])->name('home');
 // Dashboard 
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

 // Profile 
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        Route::resource('inventory', \App\Http\Controllers\Admin\InventoryController::class)
            ->only(['index', 'create', 'store']);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show', 'create', 'store']);
        Route::get('/pos', [\App\Http\Controllers\Admin\PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/checkout', [\App\Http\Controllers\Admin\PosController::class, 'checkout'])->name('pos.checkout');
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
        Route::get('/reports/revenue', [\App\Http\Controllers\Admin\DashboardController::class, 'revenueReport'])->name('reports.revenue');
        Route::get('/pos/check-customer', [\App\Http\Controllers\Admin\PosController::class, 'checkCustomer'])->name('pos.checkCustomer');


    });
require __DIR__.'/auth.php';