<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\UpdateNiagaToCmpController;
use App\Http\Controllers\Api\TphMobileApiController;
use App\Http\Controllers\Api\CMPController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['check.apikey'])->group(function () {
    Route::post('/send-report', [EmailController::class, 'sendReport'])->name('send-report-api');
    // Route::get('/test', [EmailController::class, 'sendReport'])->name('test-api');
    // Route::get('/update-naija-to-cmp', [UpdateNiagaToCmpController::class, 'updateNiageToCmp'])->name('update-naija-to-cmp-api');

    // Tambahkan route TPH Mobile di sini jika ada
    Route::post('/store-tph-koordinat', [TphMobileApiController::class, 'storeDataTPHKoordinat']);

    Route::get('/test-connection', [TphMobileApiController::class, 'testApi']);


    //cmpt tph
    Route::prefix('convert')->controller(CMPController::class)->group(function () {
        Route::get('/DatasetRegionalToJson', 'convertDatasetRegionalToJson');
        Route::get('/DatasetWilayahToJson', 'convertDatasetWilayahToJson');
        Route::get('/DatasetDeptToJson', 'convertDatasetDeptToJson');
        Route::get('/DatasetDivisiToJson', 'convertDatasetDivisiToJson');
        Route::get('/DatasetBlokToJson', 'convertDatasetBlokToJson');
        Route::get('/DatasetTPHNewToJson', 'convertDatasetTPHNewToJson');
        Route::get('/DatasetKaryawanToJson', 'convertDatasetKaryawanToJson');
        Route::get('/DatasetKemandoranToJson', 'convertDatasetKemandoranToJson');
        Route::get('/DatasetKemandoranDetailToJson', 'convertDatasetKemandoranDetailToJson');
    });

    Route::prefix('download')->controller(CMPController::class)->group(function () {
        Route::get('/DatasetRegionalJson', 'downloadDatasetRegionalJson');
        Route::get('/DatasetWilayahJson', 'downloadDatasetWilayahJson');
        Route::get('/DatasetDeptJson', 'downloadDatasetDeptJson');
        Route::get('/DatasetDivisiJson', 'downloadDatasetDivisiJson');
        Route::get('/DatasetBlokJson', 'downloadDatasetBlokJson');
        Route::get('/DatasetTPHNewJson', 'downloadDatasetTPHNewJson');
        Route::get('/DatasetKaryawanJson', 'downloadDatasetKaryawanJson');
        Route::get('/DatasetKemandoranJson', 'downloadDatasetKemandoranJson');
        Route::get('/DatasetKemandoranDetailJson', 'downloadDatasetKemandoranDetailJson');
    });

    // untuk database 
    Route::prefix('sync')->group(function () {
        Route::get('/niaga-check', [UpdateNiagaToCmpController::class, 'checkLastUpdate']);
        Route::get('/niaga-fetch', [UpdateNiagaToCmpController::class, 'fetchNiagaData']);
        Route::get('/download', [UpdateNiagaToCmpController::class, 'downloadData']);
        Route::get('/tph/latest-zip', [UpdateNiagaToCmpController::class, 'getLatestZip']);
        Route::get('/tph/checkLastupdate', [UpdateNiagaToCmpController::class, 'checkLastUpdate']);
    });


    Route::get('/exportdatajson', [UpdateNiagaToCmpController::class, 'exportDataJson']);
    Route::post('/VersioningDB', [UpdateNiagaToCmpController::class, 'VersioningDB']);
});
// Route::get('/test', [EmailController::class, 'sendReport'])->name('test-api');
