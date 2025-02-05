<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthUserCmpController;

// CMP Auth Routes
Route::get('/login', [AuthUserCmpController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthUserCmpController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthUserCmpController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Redirect unauthenticated users to login
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest:web');
