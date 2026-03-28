<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\UserOrderController;

 //Trang chủ 
Route::get('/', [HomeController::class, 'index'])->name('home');
//Danh mục sản phẩm
Route::get('/danh-muc/{slug}', [CategoryController::class, 'show'])
    ->name('category.show');
 // Thêm sản phẩm vào giỏ hàng qua Ajax

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
// Route xem chi tiết sản phẩm
Route::get('/san-pham/{slug}', [App\Http\Controllers\HomeController::class, 'show'])->name('product.show');
//thanh toán
Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/thanh-toan', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/dat-hang-thanh-cong', [CheckoutController::class, 'success'])->name('checkout.success');

 // Dashboard 
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

 // Profile 
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/orders', [UserOrderController::class, 'index'])->name('profile.orders');
    Route::get('/profile/orders/{id}', [UserOrderController::class, 'show'])->name('profile.orders.show');
    Route::post('/orders/{id}/reorder', [UserOrderController::class, 'reorder'])->name('orders.reorder');
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
        Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update_status');
});

require __DIR__.'/auth.php';