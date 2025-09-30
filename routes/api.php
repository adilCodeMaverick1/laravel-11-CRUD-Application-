<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\PaymentController;

// Protected API route
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Google OAuth routes
Route::get('auth/google', [SocialController::class, 'redirect']);
Route::get('auth/google/callback', [SocialController::class, 'handleGoogleCallback']);
Route::post('/checkout/safepay', [PaymentController::class, 'SafepayCheckout'])->name('payment.checkout');
Route::get('/payment/success/{order}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
// Route::get('/payment/cancel/{order}', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
