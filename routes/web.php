<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
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