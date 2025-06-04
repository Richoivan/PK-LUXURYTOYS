<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['user.auth'])->group(function () {
    Route::get('/user/home', [HomepageController::class, 'home'])->name('user.home');
    Route::get('/user/catalog', [HomepageController::class, 'catalog'])->name('user.catalog');
    Route::get('/user/detail/{id}', [HomepageController::class, 'detail'])->name('user.detail');
    Route::get('/user/order', [OrderController::class, 'index'])->name('user.order');
    Route::get('/user/order-detail/{id}', [OrderController::class, 'orderDetail'])->name('user.order-detail');
    Route::post('/orders/{order}/check-payment', [OrderController::class, 'checkStatus'])->name('user.check-payment');
    Route::post('/orders/{order}/cancel-order', [OrderController::class, 'cancelOrder'])->name('user.cancel-order');
    Route::post('/user/logout', [LoginController::class, 'logout'])->name('user.logout');
    Route::resource('/user/cart', CartController::class);
    Route::resource('/user/wishlist', WishlistController::class);
    Route::post('/checkout/process', [CheckoutController::class, 'checkout'])->name('checkout.process');
   });


Route::middleware(['admin.auth'])->group(function () {
  
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/order', [AdminController::class, 'order'])->name('admin.order');
    Route::get('/admin/order-detail/{id}', [AdminController::class, 'orderDetail'])->name('admin.order-detail');
    Route::post('/admin/orders/{order}/check-payment', [AdminController::class, 'checkPaymentStatus'])->name('admin.check-payment');
    Route::resource('/admin/cars', CarController::class);
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

});

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login']);
    Route::get('/admin/forget-password', [AdminController::class, 'showForgetPasswordForm'])->name('admin.forget-password');
    Route::post('/admin/forget-password', [AdminController::class, 'forgetPassword'])->name('admin.forget-password.submit');
    
    Route::get('/admin/update-password', [AdminController::class, 'showUpdatePasswordForm'])->name('admin.update-password');
    Route::post('/admin/update-password', [AdminController::class, 'updatePassword'])->name('admin.update-password.submit');

    Route::get('/user/login', [LoginController::class, 'showLoginForm'])->name('user.login');
    Route::post('/user/login', [LoginController::class, 'login']);
    Route::get('/user/register', [LoginController::class, 'showRegisterForm'])->name('user.register');
    Route::post('/user/register', [LoginController::class, 'register']);
    Route::get('/user/forget-password', [LoginController::class, 'showForgetPasswordForm'])->name('user.forget-password');
    Route::post('/user/forget-password', [LoginController::class, 'forgetPassword'])->name('user.forget-password.submit');
    
    Route::get('/user/update-password', [LoginController::class, 'showUpdatePasswordForm'])->name('user.update-password');
    Route::post('/user/update-password', [LoginController::class, 'updatePassword'])->name('user.update-password.submit');
});




