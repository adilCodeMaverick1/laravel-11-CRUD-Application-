<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RazorpayController;


Route::get('dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Route for initiating the payment from your checkout page
Route::get('/checkout/now', [PaymentController::class, 'pageCheckout'])->name('payment.page');

Route::post('/checkout/pay', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');

// Routes for handling PayFast callbacks
Route::get('/payment/success', [PaymentController::class, 'handleSuccess'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'handleCancel'])->name('payment.cancel');
Route::post('/payment/notify', [PaymentController::class, 'handleNotification'])->name('payment.notify');
Route::middleware('auth')->group(function () {
    Route::resource("blog", BlogController::class);
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
    Route::get('/show/{blog}', [BlogController::class, 'show'])->name('show.blog');
    Route::get('/checkout', 'SaleController@checkout');
    Route::get('/employee', [EmployeeController::class, 'index']);
    Route::post('/employee/search', [EmployeeController::class, 'showEmployee'])->name('employee.search');
    Route::get('/user1', [BlogController::class, 'getUserOneBlogs'])->name('user1Blog');
    Route::get('AvailableBlogs', [BlogController::class, 'availableToBuy'])->name('available.index');
    Route::get('/blogs/available-to-buy-more', [BlogController::class, 'loadMoreBlogs'])->name('blogs.loadMore');
    Route::get('/all-authors', [BlogController::class, 'allAuthors'])->name('all.authors');



    Route::post('/upload-photo', [PhotoController::class, 'upload']);
    Route::delete('/delete-photo/{filename}', [PhotoController::class, 'delete']);
    Route::get('/upload', function () {
        return view('upload');
    });

    Route::get('/test-r2-connection', function () {
        try {
            // Test connection
            $disk = Storage::disk('r2');

            // Try to list files
            $files = $disk->files();

            // Try to create a test file
            $testContent = 'Test file created at ' . now();
            $disk->put('test.txt', $testContent);

            // Check if test file exists
            $exists = $disk->exists('test.txt');

            return response()->json([
                'success' => true,
                'files' => $files,
                'test_file_created' => $exists,
                'config' => [
                    'endpoint' => config('filesystems.disks.r2.endpoint'),
                    'bucket' => config('filesystems.disks.r2.bucket'),
                    'region' => config('filesystems.disks.r2.region'),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    });

    Route::get('/direct-r2-test', function () {
        $client = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => 'auto',
            'endpoint' => env('R2_ENDPOINT'),
            'credentials' => [
                'key' => env('R2_ACCESS_KEY_ID'),
                'secret' => env('R2_SECRET_ACCESS_KEY'),
            ],
            'use_path_style_endpoint' => true,
        ]);

        try {
            $result = $client->putObject([
                'Bucket' => env('R2_BUCKET'),
                'Key' => 'direct-test.txt',
                'Body' => 'Direct test - ' . now(),
            ]);

            return response()->json([
                'success' => true,
                'result' => $result->toArray()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    });
});


Route::get('/razorpay', [RazorpayController::class, 'index']);
Route::post('/razorpay/payment', [RazorpayController::class, 'payment'])->name('razorpay.payment');
Route::post('/razorpay/success', [RazorpayController::class, 'success'])->name('razorpay.success');

//safepay
Route::get('/safepay', [PaymentController::class, 'SafepayCheckout'])->name('safepay.page');
require __DIR__ . '/auth.php';
