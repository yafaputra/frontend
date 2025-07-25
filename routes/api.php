<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Course Routes
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::get('/course-description/{id}', [CourseController::class, 'showByDescription']);

// Public Course Content Routes
Route::prefix('course-content')->group(function () {
    Route::get('/course/{id}', [CourseContentController::class, 'getByCourseDescription']);
    Route::get('/slug/{slug}', [CourseContentController::class, 'getBySlug']);
    Route::get('/navigation/{id}', [CourseContentController::class, 'getNavigation']);
    Route::get('/prev-next/{slug}', [CourseContentController::class, 'getPrevNext']);
    Route::get('/search', [CourseContentController::class, 'search']);
    Route::get('/all', [CourseContentController::class, 'index']);
});

// Public Payment Routes (untuk Midtrans callback)
Route::post('/payment/notification', [PaymentController::class, 'handleNotification']);
Route::get('/payment/finish', [PaymentController::class, 'paymentFinish']);

// Protected Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User Profile Routes
    Route::get('/user/profile', [UserProfileController::class, 'show']);
    Route::put('/user/profile', [UserProfileController::class, 'update']);
    Route::post('/user/profile', [UserProfileController::class, 'update']); // Support both PUT & POST
    Route::post('/user/change-password', [UserProfileController::class, 'changePassword']);

    // Method khusus untuk payment (jika diperlukan)
    Route::get('/user/profile-for-payment', [UserProfileController::class, 'getProfileForPayment']);

    // Payment Routes (Protected)
    Route::prefix('payment')->group(function () {
        Route::post('/create-snap-token', [PaymentController::class, 'createSnapToken']);
        Route::get('/status/{orderId}', [PaymentController::class, 'checkPaymentStatus']);
        Route::get('/user-payments', [PaymentController::class, 'getUserPayments']);
    });
    Route::post('/midtrans/notification', [PaymentController::class, 'handleNotification']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Protected Course Content Routes (jika diperlukan autentikasi untuk akses konten)
    Route::prefix('protected/course-content')->group(function () {
        Route::get('/course/{id}', [CourseContentController::class, 'getByCourseDescription']);
        Route::post('/progress/{courseId}', function (Request $request, $courseId) {
            // Save user progress
            $user = $request->user();
            $progress = $request->input('progress', []);

            // Logic to save progress to database
            // You can create a UserProgress model for this

            return response()->json([
                'success' => true,
                'message' => 'Progress saved successfully'
            ]);
        });

        Route::get('/progress/{courseId}', function (Request $request, $courseId) {
            // Get user progress
            $user = $request->user();

            // Logic to retrieve progress from database

            return response()->json([
                'success' => true,
                'data' => [
                    'completed_materials' => [],
                    'current_material' => null,
                    'progress_percentage' => 0
                ]
            ]);
        });
    });
});
