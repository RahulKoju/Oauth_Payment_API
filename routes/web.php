<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EsewaController;
use App\Http\Controllers\KhaltiController;
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

    Route::post('/checkout/khalti', [KhaltiController::class, 'checkout'])->name('khalti.checkout');
    Route::post('/khalti/payment/initiate', [KhaltiController::class, 'initiatePayment'])->name('khalti.payment.initiate');
    Route::get('/khalti/payment/verify', [KhaltiController::class, 'verifyPayment'])->name('khalti.payment.verify');
    Route::get('/khalti/payment/success', [KhaltiController::class, 'success'])->name('khalti.success');
    Route::get('/khalti/payment/failure', [KhaltiController::class, 'failure'])->name('khalti.failure');
});

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
