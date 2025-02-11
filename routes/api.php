<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\UpdateNiageToCmpController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/send-report', [EmailController::class, 'sendReport'])->name('send-report-api');

Route::get('/test', [EmailController::class, 'sendReport'])->name('test-api');

Route::get('/update-naija-to-cmp', [UpdateNiageToCmpController::class, 'updateNiageToCmp'])->name('update-naija-to-cmp-api');
