<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('data/{blog?}', [ApiController::class, 'getBlogs']);
Route::post('data/post', [ApiController::class, 'create']);
Route::put('data/update', [ApiController::class, 'update']);
Route::get('data/search/{name}', [ApiController::class, 'search']);
Route::delete('data/delete', [ApiController::class, 'delete']);
