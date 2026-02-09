<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\ProductController;

use App\Http\Controllers\SalePageController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ChangeLogController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Http\Middleware\RedirectIfNotEmployee;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product-by-category/product-details/{product_id}', [SalePageController::class, 'showBookDetails'])->name('sale.showBookDetails');
Route::get('/product-by-category/{category}', [SalePageController::class, 'showBookByCategory'])->name('sale.showBookByCategory');
Route::get('/product-by-type/{producttype_id}', [SalePageController::class, 'showBookByType'])->name('sale.showBookByType');
Route::get('/product-details/{product_title_id}', [SalePageController::class, 'showBookDetails'])->name('sale.showBookDetails');
Route::get('/discounts', [DiscountController::class, 'listDiscounts'])->name('discounts.list');
Route::get('/search', [SalePageController::class, 'search'])->name('search');

Route::middleware([RedirectIfNotAuthenticated::class])->group(function () {
    Route::resource('cart', CartController::class);
    Route::resource('order', OrderController::class);
    Route::post('/check-discount', [DiscountController::class, 'checkDiscount'])->name('check.discount');
    Route::post('/buy-now', [OrderController::class, 'buyNow'])->name('order.buyNow');
    Route::post('/buy-now-create', [OrderController::class, 'buyNowCreate'])->name('order.buyNowCreate');
    Route::post('/cancelorder/{order}', [OrderController::class, 'cancelOrder'])->name('order.cancelorder');
    Route::get('/orderinfor', [OrderController::class, 'orderInfor'])->name('order.orderinfor');
});

// Route để kiểm tra trạng thái đăng nhập
Route::get('/check-login-status', [AccountController::class, 'checkLoginStatus']);

Route::get('/admin', function () {
    return view('master.admin');
})->name('home.admin');

Route::group(['prefix' => 'account'], function () {

    Route::get('/login', [AccountController::class, 'login'])->name('account.login');
    Route::post('/login', [AccountController::class, 'checkLogin']);
    Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');

    Route::get('/verify-account/{email}', [AccountController::class, 'verify'])->name('account.verify');
    Route::get('/register', [AccountController::class, 'register'])->name('account.register');
    Route::post('/register', [AccountController::class, 'checkRegister']);

    Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('/profile', [AccountController::class, 'checkProfile']);

    Route::middleware([RedirectIfNotAuthenticated::class])->group(function () {
        Route::get('/change-password', [AccountController::class, 'changePassword'])->name('account.change-password');
        Route::post('/change-password', [AccountController::class, 'checkChangePassword']);

        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::post('/profile', [AccountController::class, 'checkProfile']);
    });

    Route::get('/forgot-password', [AccountController::class, 'forgotPassword'])->name('account.forgot-password');
    Route::post('/forgot-password', [AccountController::class, 'checkForgotPassword']);

    Route::get('/reset-password/{token}', [AccountController::class, 'resetPassword'])->name('account.reset-password');
    Route::post('/reset-password/{token}', [AccountController::class, 'checkResetPassword']);
});

Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'checkLogin']);

Route::group(['prefix' => 'admin', 'middleware' => [RedirectIfNotEmployee::class]], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/statistics', [AdminController::class, 'index'])->name('admin.statistics');
    Route::get('/sales-report', [AdminController::class, 'salesReport'])->name('admin.salesReport');
    Route::get('/export-sales-report', [AdminController::class, 'exportSalesReport'])->name('admin.exportSalesReport');

    Route::resource('product', ProductController::class);
    Route::get('/product-date-image/{image}', [ProductController::class, 'destroyImage'])->name('product.destroyImage');

    Route::resource('discount', DiscountController::class);

    Route::get('/change-logs', [ChangeLogController::class, 'index'])->name('change-logs.index');
    Route::post('/change-logs/revert/{id}', [ChangeLogController::class, 'revert'])->name('change-logs.revert');
});

Route::get('/test', function () {
    return view('TimKiemSP');
});

Route::get('/test1', function () {
    return view('layout.partials.Header_Employee');
});