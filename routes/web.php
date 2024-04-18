<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Models\blogs;

Route::get('/', function () {
    return view('welcome');
});

Route:: resource("blog",BlogController::class);
