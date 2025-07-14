<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;

// Protected API route
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Google OAuth routes
Route::get('auth/google', [SocialController::class, 'redirect']);
Route::get('auth/google/callback', [SocialController::class, 'handleGoogleCallback']);
