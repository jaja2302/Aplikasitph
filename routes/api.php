<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\UpdateNiageToCmpController;
use App\Http\Controllers\Api\TphMobileApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['check.apikey'])->group(function () {
    Route::post('/send-report', [EmailController::class, 'sendReport'])->name('send-report-api');
    Route::get('/test', [EmailController::class, 'sendReport'])->name('test-api');
    Route::get('/update-naija-to-cmp', [UpdateNiageToCmpController::class, 'updateNiageToCmp'])->name('update-naija-to-cmp-api');

    // Tambahkan route TPH Mobile di sini jika ada
    Route::post('/store-tph-koordinat', [TphMobileApiController::class, 'storeDataTPHKoordinat']);

    Route::get('/test-connection', [TphMobileApiController::class, 'testApi']);
});
