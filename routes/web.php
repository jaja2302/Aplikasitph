<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthUserCmpController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardAjaxController;
// use App\Http\Controllers\Auth\AuthPenggunaController;
use App\Http\Controllers\Maps\GisBlokController;
// Guest routes (only accessible when not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthUserCmpController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthUserCmpController::class, 'login'])->name('login.submit');
});

// Auth routes (only accessible when logged in)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthUserCmpController::class, 'logout'])->name('logout');
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



    Route::get('/maps/gisblok', [GisBlokController::class, 'index'])->name('maps-management');
    Route::get('/maps/getafdeling', [GisBlokController::class, 'getAfdeling'])->name('gis.getAfdeling');
    Route::get('/maps/plotsblok', [GisBlokController::class, 'getPlots'])->name('gis.getPlotsblok');
    Route::post('/maps/save-plotsblok', [GisBlokController::class, 'savePlots'])->name('gis.savePlotsblok');
});

// Redirect root to login or dashboard based on auth status
Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'dashboard' : 'login');
});
