<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;



Route::get('dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route:: resource("blog",BlogController::class);
    Route::get('/', [BlogController::class, 'index']);
    Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
    Route::get('/report', [ReportController::class, 'report'])->name('report.index');
    Route::get('/reports', [ReportController::class, 'generateReport'])->name('generate.report');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/check', [BlogController::class, 'check'])->name('check.index');
    Route::get('/addpropage', [BlogController::class, 'addpropage'])->name('check.addpro');
    Route::post('/add-product', [BlogController::class, 'addProduct'])->name('check.addProduct');
Route::get('/checkout', 'SaleController@checkout');
Route::get('/employee',[EmployeeController::class,'index']);
Route::post('/employee/search',[EmployeeController::class,'showEmployee'])->name('employee.search');

});

require __DIR__.'/auth.php';
