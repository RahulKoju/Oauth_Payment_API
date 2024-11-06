<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EsewaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::post('/checkout/esewa', [EsewaController::class, 'checkout'])->name('esewa.checkout');
    Route::get('/payment/esewa/success', [EsewaController::class, 'success'])->name('esewa.success');
    Route::get('/payment/esewa/failure', [EsewaController::class, 'failure'])->name('esewa.failure');
    Route::get('/payment/esewa/status', [EsewaController::class, 'checkStatus'])->name('esewa.status');
});

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
