<?php

use App\Http\Controllers\AppMovil\DatabaseController;
use App\Http\Controllers\AppMovil\MovementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LockerController;
use Illuminate\Support\Facades\Route;

Route::get('/database', [DatabaseController::class, 'index']);
Route::post('/auth', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {

    Route::get('/auth', [AuthController::class, 'getUser']);
    Route::prefix('dashboard')->group(function () {
        Route::get('/info', [DashboardController::class, 'info'])->name('configurations.dashboard.info');
    });
    Route::prefix('locker')->group(function () {
        Route::post('/data-table', [LockerController::class, 'dataTable'])->name('configurations.locker.data-table');
        Route::post('/get-status', [LockerController::class, 'getStatus'])->name('configurations.locker.get-status');
    });

});
Route::get('/database', [DatabaseController::class, 'index']);
Route::post('/movement', [MovementController::class, 'store']);
Route::put('/movement/{id}', [MovementController::class, 'update']);
