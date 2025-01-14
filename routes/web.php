<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthPenggunaController;

// Auth Routes
Route::get('/login', [AuthPenggunaController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthPenggunaController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthPenggunaController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth:pengguna'])->group(function () {
    // Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
// Redirect unauthenticated users to login
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest:pengguna');
