<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeluarController;
use App\Http\Controllers\MasukController;
use App\Http\Controllers\LoginController;


Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
});

Route::group(['middleware' => 'auth'], function () {
Route::resource('/products', \App\Http\Controllers\ProductController::class);
Route::resource('/kategori', \App\Http\Controllers\KategoriController::class);
Route::resource('/category', \App\Http\Controllers\CategoryController::class);
Route::resource('/barang', \App\Http\Controllers\BarangController::class);
Route::resource('barangmasuk', MasukController::class);
Route::resource('barangkeluar', KeluarController::class);
Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('logout', [LoginController::class,'logout'])->name('logout');
});