<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/{any}', function () {
    return view('homepage'); // atau view utama Vue-mu
})->where('homepage', '.*');
Route::get('/', function () {
    return view('homepage');
});
Route::get('/produk', function () {
    return view('produk'); // buat produk.blade.php
});
Route::get('/produk', function () {
    return view('produk'); // Nanti buat produk.blade.php
});
Route::get('/tentang', function () {
    return view('tentang'); // 
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
