<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Halaman utama (Vue mount di sini misalnya)
Route::get('/', function () {
    return view('homepage');
});

// Halaman tambahan (jika pakai blade)
Route::get('/produk', function () {
    return view('produk');
});

Route::get('/tentang', function () {
    return view('tentang');
});

// Route fallback untuk semua frontend SPA (Vue)
Route::get('/{any}', function () {
    return view('homepage');
})->where('any', '^(?!api|admin).*$');

// Auth (kalau ini ditujukan untuk frontend, lebih baik dipindah ke api.php)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::get('/adminlte', function () {
    return view('adminlte'); // atau nama view lainnya
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', UserController::class);
});
