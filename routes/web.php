<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthUserCmpController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardAjaxController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthPenggunaController;
// Guest routes (only accessible when not logged in)
Route::middleware('guest:pengguna')->group(function () {
    Route::get('/login', [AuthPenggunaController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthPenggunaController::class, 'login'])->name('login.submit');
});

// Auth routes (only accessible when logged in)
Route::middleware('auth:pengguna')->group(function () {
    Route::post('/logout', [AuthPenggunaController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardAjaxController::class, 'index'])->name('dashboard');

    // Changed from API routes to named routes
    Route::get('/dashboard/regional', [DashboardAjaxController::class, 'getRegional'])->name('dashboard.regional');
    Route::get('/dashboard/wilayah/{regionalId}', [DashboardAjaxController::class, 'getWilayah'])->name('dashboard.wilayah');
    Route::get('/dashboard/estate/{wilayahId}', [DashboardAjaxController::class, 'getEstate'])->name('dashboard.estate');
    Route::get('/dashboard/afdeling/{estateId}', [DashboardAjaxController::class, 'getAfdeling'])->name('dashboard.afdeling');
    Route::get('/dashboard/blok/{afdelingId}', [DashboardAjaxController::class, 'getBlok'])->name('dashboard.blok');
    Route::get('/dashboard/plot-map/{afdelingId}', [DashboardAjaxController::class, 'getPlotMap'])->name('dashboard.plot-map');
    Route::get('/dashboard/tph-coordinates/{estateId}/{afdelingId}', [DashboardAjaxController::class, 'getTPHCoordinates'])->name('dashboard.tph-coordinates');
    Route::post('/dashboard/update-tph/{id}', [DashboardAjaxController::class, 'updateTPH'])->name('dashboard.update-tph');
    Route::delete('/dashboard/delete-tph/{id}', [DashboardAjaxController::class, 'deleteTPH'])->name('dashboard.delete-tph');
    Route::get('/dashboard/legend-info/{estateId}/{afdelingId}', [DashboardAjaxController::class, 'getLegendInfo'])->name('dashboard.legend-info');
});

// Redirect root to login or dashboard based on auth status
Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'dashboard' : 'login');
});
