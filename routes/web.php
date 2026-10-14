<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ───────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/store', [HomeController::class, 'store'])->name('store');
Route::get('/api/search/suggestions', [HomeController::class, 'searchSuggestions'])->name('api.search.suggestions');
Route::get('/categories', [HomeController::class, 'categories'])->name('categories');
Route::get('/game/{game:slug}', [GameController::class, 'show'])->name('game.show');
Route::get('/game/{game:slug}/dlc', [GameController::class, 'dlc'])->name('game.dlc');
Route::get('/game/{game:slug}/bundle', [GameController::class, 'bundle'])->name('game.bundle');
Route::get('/creator/{name}', [HomeController::class, 'creator'])->name('creator.show');
Route::view('/bantuan', 'bantuan')->name('bantuan');
Route::post('/bantuan/chat', [\App\Http\Controllers\AiChatController::class, 'chat'])->name('bantuan.chat');

// ─── Midtrans Webhook (no CSRF) ───────────────────────────────────────────────
Route::post('/checkout/callback', [CheckoutController::class, 'callback'])
    ->name('checkout.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ─── Authenticated User Routes ───────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{game}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{game}', [CartController::class, 'toggle'])->name('cart.toggle');
    Route::post('/cart/{game}/bundle', [CartController::class, 'addBundle'])->name('cart.bundle');
    Route::delete('/cart/{game}/bundle', [CartController::class, 'removeBundle'])->name('cart.removeBundle');
    Route::delete('/cart/{game}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/token', [CheckoutController::class, 'getToken'])->name('checkout.token');
    Route::post('/checkout/finalize', [CheckoutController::class, 'finalize'])->name('checkout.finalize');

    // Library
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::post('/library/{library}/favorite', [LibraryController::class, 'toggleFavorite'])->name('library.favorite');
    Route::post('/library/{library}/license', [LibraryController::class, 'showLicense'])->name('library.license');
    Route::post('/library/{game}/claim', [LibraryController::class, 'claimFree'])->name('library.claim');

    // Invoice
    Route::post('/invoice/{transaction}/download', [InvoiceController::class, 'download'])
        ->name('invoice.download');

    // Reviews
    Route::post('/review/{game}', [ReviewController::class, 'store'])->name('review.store');
    Route::put('/review/{review}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/review/{review}', [ReviewController::class, 'destroy'])->name('review.destroy');

    // Refund
    Route::get('/refund/{detail}', [RefundController::class, 'show'])->name('refund.show');
    Route::post('/refund/{detail}', [RefundController::class, 'request'])->name('refund.request');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    // AJAX Profile Routes for OTP Unified System
    Route::post('/profile/ajax/password/send-otp', [ProfileController::class, 'ajaxSendPasswordOtp'])->name('profile.ajax.password.send')->middleware('throttle:3,15');
    Route::post('/profile/ajax/password/verify-and-update', [ProfileController::class, 'ajaxVerifyPasswordAndUpdate'])->name('profile.ajax.password.verify');
    
    Route::post('/profile/ajax/pin/send-otp', [ProfileController::class, 'ajaxSendPinOtp'])->name('profile.ajax.pin.send')->middleware('throttle:3,15');
    Route::post('/profile/ajax/pin/verify-and-update', [ProfileController::class, 'ajaxVerifyPinAndUpdate'])->name('profile.ajax.pin.verify');
    
    Route::post('/profile/ajax/email/send-old-otp', [ProfileController::class, 'ajaxSendEmailOtp'])->name('profile.ajax.email.send')->middleware('throttle:3,15');
    Route::post('/profile/ajax/email/verify-and-update', [ProfileController::class, 'ajaxVerifyEmailAndUpdate'])->name('profile.ajax.email.verify');
    Route::post('/profile/ajax/pin/forgot-otp', [ProfileController::class, 'ajaxSendForgotPinOtp'])->name('profile.ajax.pin.forgot.send');
    Route::post('/profile/ajax/pin/forgot-verify', [ProfileController::class, 'ajaxVerifyForgotPin'])->name('profile.ajax.pin.forgot.verify');
    Route::post('/profile/ajax/pin/reset', [ProfileController::class, 'ajaxResetPin'])->name('profile.ajax.pin.reset');
    
    Route::post('/profile/orders/check-statuses', [ProfileController::class, 'checkStatuses'])->name('profile.orders.check-statuses');
    Route::get('/profile/orders/{id}/detail', [ProfileController::class, 'getTransactionDetail'])->name('profile.orders.detail');
    Route::post('/profile/orders/{id}/cancel', [ProfileController::class, 'cancelOrder'])->name('profile.orders.cancel');
    Route::post('/profile/orders/{id}/cancel-duplicates', [ProfileController::class, 'cancelDuplicates'])->name('profile.orders.cancel-duplicates');
    
    // Cancel refund
    Route::post('/refund/{detail}/cancel', [RefundController::class, 'cancel'])->name('refund.cancel');
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Games CRUD
    Route::post('/games/fetch-image', [Admin\GameController::class, 'fetchImage'])->name('games.fetch-image');
    Route::resource('games', Admin\GameController::class);
    Route::get('/galleries/{id}/delete', [Admin\GameController::class, 'destroyGallery'])->name('galleries.delete');

    // Transactions
    Route::get('/transactions', [Admin\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/report/pdf', [Admin\TransactionController::class, 'reportPdf'])->name('transactions.report.pdf');
    Route::get('/transactions/{transaction}/invoice', [Admin\TransactionController::class, 'invoice'])->name('transactions.invoice');

    // Users
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');

    // Refunds
    Route::get('/refunds', [Admin\RefundController::class, 'index'])->name('refunds.index');
    Route::post('/refunds/{detail}/approve', [Admin\RefundController::class, 'approve'])->name('refunds.approve');
    Route::post('/refunds/{detail}/reject', [Admin\RefundController::class, 'reject'])->name('refunds.reject');
});

// ─── Breeze Auth Routes ───────────────────────────────────────────────────────
require __DIR__.'/auth.php';
