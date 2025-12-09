<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

// Authentication Routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Registration Routes with OTP
Route::get('register/otp', [App\Http\Controllers\Auth\OtpController::class, 'showOtpForm'])->name('register.otp');
Route::post('register/otp', [App\Http\Controllers\Auth\OtpController::class, 'verifyOtp'])->name('register.verify-otp');
Route::get('register/resend-otp', [App\Http\Controllers\Auth\OtpController::class, 'resendOtp'])->name('register.resend-otp');

// Password Reset Routes
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Password Confirmation Routes
Route::get('password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'confirm']);

// Email Verification Routes
Route::get('email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Address Routes
    Route::get('/profile/addresses', [App\Http\Controllers\ProfileController::class, 'addresses'])->name('profile.addresses.index');
    Route::get('/profile/addresses/create', [App\Http\Controllers\ProfileController::class, 'createAddress'])->name('profile.addresses.create');
    Route::post('/profile/addresses', [App\Http\Controllers\ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::get('/profile/addresses/{address}/edit', [App\Http\Controllers\ProfileController::class, 'editAddress'])->name('profile.addresses.edit');
    Route::put('/profile/addresses/{address}', [App\Http\Controllers\ProfileController::class, 'updateAddress'])->name('profile.addresses.update');
    Route::delete('/profile/addresses/{address}', [App\Http\Controllers\ProfileController::class, 'destroyAddress'])->name('profile.addresses.destroy');
    Route::put('/profile/addresses/{address}/default', [App\Http\Controllers\ProfileController::class, 'setDefaultAddress'])->name('profile.addresses.default');
});

// Product routes
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

// Wishlist routes
Route::resource('wishlist', App\Http\Controllers\WishlistController::class)->only(['index', 'store', 'destroy']);

// Compare routes
Route::resource('compare', App\Http\Controllers\CompareController::class)->only(['index', 'store', 'destroy']);
Route::delete('/compare/clear', [App\Http\Controllers\CompareController::class, 'clear'])->name('compare.clear');

// Recently viewed routes
Route::get('/recently-viewed', [App\Http\Controllers\RecentlyViewedController::class, 'index'])->name('recently-viewed.index');
Route::post('/recently-viewed', [App\Http\Controllers\RecentlyViewedController::class, 'store'])->name('recently-viewed.store');
Route::delete('/recently-viewed/clear', [App\Http\Controllers\RecentlyViewedController::class, 'clear'])->name('recently-viewed.clear');

// Cart routes
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
Route::put('/cart/{id}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');

// Checkout routes
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/paypal/redirect', [App\Http\Controllers\CheckoutController::class, 'paypalRedirect'])->name('checkout.paypal.redirect');
Route::get('/checkout/paypal/callback', [App\Http\Controllers\CheckoutController::class, 'paypalCallback'])->name('checkout.paypal.callback');
Route::post('/paypal/ipn', [App\Http\Controllers\CheckoutController::class, 'paypalIPN'])->name('paypal.ipn');
Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

// Order routes
Route::resource('orders', App\Http\Controllers\OrderController::class)->only(['index', 'show']);

// Test route
Route::get('/test', function () {
    return 'Test route working';
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show']);
    Route::put('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/orders/{order}/print', [App\Http\Controllers\Admin\OrderController::class, 'print'])->name('orders.print');
    Route::resource('images', App\Http\Controllers\Admin\ImageController::class)->only(['index', 'store', 'destroy']);
    Route::post('/product-images/{id}/set-primary', [App\Http\Controllers\Admin\ImageController::class, 'setPrimary'])->name('product-images.set-primary');
    Route::get('/otps', [App\Http\Controllers\Admin\OtpController::class, 'index'])->name('otps.index');
});
