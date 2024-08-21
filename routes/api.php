<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DealsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('web')->group(function () {
    Route::get('/accounts', [AccountController::class, 'index']);
});

Route::middleware('web')->group(function () {
    Route::post('/accounts', [AccountController::class, 'store']);
});

Route::middleware('web')->group(function () {
    Route::post('/deals', [DealsController::class, 'store']);
});