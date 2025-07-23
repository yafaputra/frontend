<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;

// Route API untuk autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User Profile Routes
    Route::get('/profile', [UserProfileController::class, 'show']);
    Route::put('/profile/update', [UserProfileController::class, 'update']);

    // Ganti Password (Change Password)
    Route::middleware('auth:sanctum')->post('/profile/change-password', [UserProfileController::class, 'changePassword']);

    // Payment Routes (Protected)
    Route::post('/checkout', [PaymentController::class, 'createSnapToken']);
    Route::get('/payments', [PaymentController::class, 'getUserPayments']);
    Route::get('/payments/status/{orderId}', [PaymentController::class, 'checkPaymentStatus']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Course Routes (Public)
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::get('/course-description/{id}', [CourseController::class, 'showByDescription']);

// Midtrans Webhook (Public - no auth required for webhook)
Route::post('/midtrans/notification', [PaymentController::class, 'handleNotification']);

// Payment finish URL (Public)
Route::get('/payment/finish', function(Request $request) {
    return view('payment.finish', [
        'order_id' => $request->order_id,
        'status_code' => $request->status_code,
        'transaction_status' => $request->transaction_status
    ]);
});

// Test route untuk memastikan Midtrans config berjalan
Route::get('/test-midtrans', function() {
    return response()->json([
        'midtrans_config' => [
            'merchant_id' => config('services.midtrans.merchant_id'),
            'client_key' => config('services.midtrans.client_key'),
            'server_key' => substr(config('services.midtrans.server_key'), 0, 10) . '...',
            'is_production' => config('services.midtrans.is_production'),
            'snap_url' => config('services.midtrans.snap_url')
        ]
    ]);
});
