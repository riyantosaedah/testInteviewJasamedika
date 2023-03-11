<?php

use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\RegistrasiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('auth/login');
});
Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('kelurahan', KelurahanController::class);
    Route::get('kelurahan-hapus/{id}', [KelurahanController::class,'destroy'])->name('kelurahan.hapus');
    Route::resource('registrasi', RegistrasiController::class);
    Route::get('registrasi-hapus/{id}', [RegistrasiController::class,'destroy'])->name('registrasi.hapus');
});
