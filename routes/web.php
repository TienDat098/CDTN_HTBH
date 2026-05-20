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
use App\Http\Controllers\ProductController as ClientProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\ChatController;

// Trang chủ 
Route::get('/', [App\Http\Controllers\StoreController::class, 'index'])->name('home');

// Danh mục sản phẩm
Route::get('/danh-muc/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

// Route xem chi tiết sản phẩm (Client)
Route::get('/san-pham/{slug}', [ClientProductController::class, 'show'])->name('product.show');
Route::get('/danh-sach-san-pham', [ClientProductController::class, 'index'])->name('product.index');
// Thanh toán (ĐÃ GỘP CHUNG VÀO ĐÂY VÀ THÊM ROUTE AJAX)
Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/thanh-toan', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/dat-hang-thanh-cong', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/thanh-toan/ap-dung-ma', [CheckoutController::class, 'applyPromotion'])->name('checkout.apply_promotion');
Route::get('/thanh-toan/payos-return', [CheckoutController::class, 'payosReturn'])->name('checkout.payos_return');
// Dashboard 
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Search 
Route::get('/search/suggest', [SearchController::class, 'suggest']);
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Blog Frontend
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blogs.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blogs.show');

// Liên hệ
Route::get('/lien-he', [\App\Http\Controllers\ContactController::class, 'index'])->name('contacts.index');
Route::post('/lien-he', [\App\Http\Controllers\ContactController::class, 'store'])->name('contacts.store');

// Profile 
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/orders', [UserOrderController::class, 'index'])->name('profile.orders');
    Route::get('/profile/orders/{id}', [UserOrderController::class, 'show'])->name('profile.orders.show');
    Route::post('/orders/{id}/reorder', [UserOrderController::class, 'reorder'])->name('orders.reorder');
});

Route::middleware(['web'])->group(function () {
    Route::get('/chat/messages/{receiver_id}', [ChatController::class, 'fetchMessages']);
    Route::post('/chat/messages', [ChatController::class, 'sendMessage']);
});
// Admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin,staff'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        // Quản lý sản phẩm
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        // Quản lý đơn hàng
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
        // Cập nhật trạng thái đơn hàng
        Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update_status');
        // Quản lý khuyến mãi
        Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);
        // Quản lý bài viết
        Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class);
        // Quản lý liên hệ
        Route::get('/contacts', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::patch('/contacts/{id}/status', [\App\Http\Controllers\Admin\ContactController::class, 'updateStatus'])->name('contacts.update_status');
        Route::delete('/contacts/{id}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
        Route::post('/contacts/{id}/reply', [\App\Http\Controllers\Admin\ContactController::class, 'reply'])->name('contacts.reply');
        //Pos bán hàng
        Route::get('/pos', [\App\Http\Controllers\Admin\PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/checkout', [\App\Http\Controllers\Admin\PosController::class, 'checkout'])->name('pos.checkout');
        Route::get('/pos/check-customer', [\App\Http\Controllers\Admin\PosController::class, 'checkCustomer'])->name('pos.checkCustomer');
        Route::get('/chat', [App\Http\Controllers\ChatController::class, 'adminChat'])->name('chat');
});
//  CHỈ CÓ ADMIN
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin']) 
    ->group(function () {
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class); // Danh mục
        Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class); // Thương hiệu
        Route::resource('inventory', \App\Http\Controllers\Admin\InventoryController::class)->only(['index', 'create', 'store']); // Tồn kho
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show', 'create', 'store']); // Tài khoản
        Route::get('/reports/revenue', [\App\Http\Controllers\Admin\DashboardController::class, 'revenueReport'])->name('reports.revenue'); // Báo cáo doanh thu
});
require __DIR__.'/auth.php';