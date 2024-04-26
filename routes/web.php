<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ReportController;
use App\Models\blogs;

Route::get('/', [BlogController::class, 'index']);
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/report', [ReportController::class, 'report'])->name('report.index');
Route::get('/reports', [ReportController::class, 'generateReport'])->name('generate.report');



  


Route:: resource("blog",BlogController::class);
