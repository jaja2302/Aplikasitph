<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthUserCmpController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardAjaxController;
// use App\Http\Controllers\Auth\AuthPenggunaController;
use App\Http\Controllers\Maps\GisBlokController;
// Guest routes (only accessible when not logged in)
use App\Http\Controllers\FetchController;
use App\Http\Controllers\TphManagementController;
use App\Http\Controllers\LocationController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthUserCmpController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthUserCmpController::class, 'login'])->name('login.submit');
});

// Auth routes (only accessible when logged in)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthUserCmpController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardAjaxController::class, 'index'])->name('dashboard');

    // Changed from API routes to named routes
    // Route::get('/dashboard/regional', [DashboardAjaxController::class, 'getRegional'])->name('dashboard.regional');
    // Route::get('/dashboard/wilayah/{regionalId}', [DashboardAjaxController::class, 'getWilayah'])->name('dashboard.wilayah');
    // Route::get('/dashboard/estate/{wilayahId}', [DashboardAjaxController::class, 'getEstate'])->name('dashboard.estate');
    // Route::get('/dashboard/afdeling/{estateId}', [DashboardAjaxController::class, 'getAfdeling'])->name('dashboard.afdeling');
    // Route::get('/dashboard/blok/{afdelingId}', [DashboardAjaxController::class, 'getBlok'])->name('dashboard.blok');
    Route::get('/dashboard/plot-map/{afdelingId}', [DashboardAjaxController::class, 'getPlotMap'])->name('dashboard.plot-map');
    Route::get('/dashboard/tph-coordinates/{estateId}/{afdelingId}', [DashboardAjaxController::class, 'getTPHCoordinates'])->name('dashboard.tph-coordinates');
    Route::post('/dashboard/update-tph/{id}', [DashboardAjaxController::class, 'updateTPH'])->name('dashboard.update-tph');
    Route::delete('/dashboard/delete-tph/{id}', [DashboardAjaxController::class, 'deleteTPH'])->name('dashboard.delete-tph');
    Route::get('/dashboard/legend-info/{estateId}/{afdelingId}', [DashboardAjaxController::class, 'getLegendInfo'])->name('dashboard.legend-info');

    Route::get('/maps/gisblok', [GisBlokController::class, 'index'])->name('maps-management');
    Route::get('/maps/getafdeling', [GisBlokController::class, 'getAfdeling'])->name('gis.getAfdeling');
    Route::get('/maps/plotsblok', [GisBlokController::class, 'getPlots'])->name('gis.getPlotsblok');
    Route::post('/maps/save-plotsblok', [GisBlokController::class, 'savePlots'])->name('gis.savePlotsblok');

    Route::get('/fetch-data', [FetchController::class, 'fetchNiagaData'])->name('fetch-data');

    Route::get('/locations/regional', [LocationController::class, 'getRegional'])->name('locations.regional');
    Route::get('/locations/wilayah/{regionalId}', [LocationController::class, 'getWilayah'])->name('locations.wilayah');
    Route::get('/locations/estate/{wilayahId}', [LocationController::class, 'getEstate'])->name('locations.estate');
    Route::get('/locations/afdeling/{estateId}', [LocationController::class, 'getAfdeling'])->name('locations.afdeling');
    Route::get('/locations/blok/{afdelingId}', [LocationController::class, 'getBlok'])->name('locations.blok');



    Route::get('/tph-management', [TphManagementController::class, 'dashboard'])->name('tph-management');
    Route::get('/tph-management/get-tabel', [TphManagementController::class, 'GetTabel'])->name('tph-management.get-tabel');
    Route::post('/tph-management/delete', [TphManagementController::class, 'delete'])->name('tph-management.delete');
    Route::post('/tph-management/store', [TphManagementController::class, 'store'])->name('tph-management.store');
    Route::post('/tph-management/batch-delete', [TPHManagementController::class, 'batchDelete'])
        ->name('tph-management.batch-delete');
});

// Redirect root to login or dashboard based on auth status
Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'dashboard' : 'login');
});
