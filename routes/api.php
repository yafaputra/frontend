<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserProfileController;
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
});

// Course Routes (Public)
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

// Route tambahan untuk mengambil data berdasarkan CourseDescription ID
Route::get('/course-description/{id}', [CourseController::class, 'showByDescription']);


// logout 
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
