<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/send-report', [EmailController::class, 'sendReport'])->name('send-report-api');

Route::get('/test', [EmailController::class, 'sendReport'])->name('test-api');
