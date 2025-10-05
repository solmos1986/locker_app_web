<?php

use App\Http\Controllers\AppMovil\DatabaseController;
use App\Http\Controllers\AppMovil\MovementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ControllerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoorController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\MovementController as ControllersMovementController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/database', [DatabaseController::class, 'index']);
Route::post('/auth', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {

    Route::get('/auth', [AuthController::class, 'getUser']);
    Route::prefix('dashboard')->group(function () {
        Route::get('/info', [DashboardController::class, 'info'])->name('configurations.dashboard.info');
    });
    Route::prefix('locker')->group(function () {
        Route::get('/requirement', [LockerController::class, 'requirement'])->name('configurations.locker.requirement');
        Route::post('/data-table', [LockerController::class, 'dataTable'])->name('configurations.locker.data-table');
        Route::post('', [LockerController::class, 'store'])->name('configurations.locker.get-status');
        Route::get('{id}', [LockerController::class, 'edit'])->name('configurations.locker.edit');
        Route::put('{id}', [LockerController::class, 'update'])->name('configurations.locker.update');
        Route::delete('{id}', [LockerController::class, 'destroy'])->name('configurations.locker.destroy');
    });

    Route::prefix('user')->group(function () {
        Route::post('/data-table', [UsersController::class, 'dataTable'])->name('configurations.user.data-table');
        Route::get('/requeriment', [UsersController::class, 'requeriment'])->name('configurations.user.requeriment');
        Route::post('', [UsersController::class, 'store'])->name('configurations.user.store');
        Route::get('{id}', [UsersController::class, 'edit'])->name('configurations.user.edit');
        Route::put('{id}', [UsersController::class, 'update'])->name('configurations.user.update');
        Route::delete('{id}', [UsersController::class, 'destroy'])->name('configurations.user.destroy');
    });

    Route::prefix('controller')->group(function () {
        Route::post('/data-table', [ControllerController::class, 'dataTable'])->name('configurations.controller.data-table');
    });

    Route::prefix('door')->group(function () {
        Route::post('/requirement', [DoorController::class, 'requirement'])->name('configurations.door.requirement');
        Route::post('/data-table', [DoorController::class, 'dataTable'])->name('configurations.door.data-table');
        Route::post('', [DoorController::class, 'store'])->name('configurations.door.store');
        Route::get('{id}', [DoorController::class, 'edit'])->name('configurations.door.edit');
        Route::put('{id}', [DoorController::class, 'update'])->name('configurations.door.update');
        Route::delete('{id}', [DoorController::class, 'destroy'])->name('configurations.door.destroy');
    });

    Route::prefix('locker')->group(function () {
        Route::post('/data-table', [LockerController::class, 'dataTable'])->name('configurations.locker.data-table');
        Route::post('/get-status', [LockerController::class, 'getStatus'])->name('configurations.locker.get-status');
    });
    Route::prefix('movement')->group(function () {
        Route::post('/data-table', [ControllersMovementController::class, 'dataTable'])->name('configurations.locker.data-table');
        Route::post('/get-status', [LockerController::class, 'getStatus'])->name('configurations.locker.get-status');
    });

});
Route::get('/database', [DatabaseController::class, 'index']);
Route::post('/movement', [MovementController::class, 'store']);
Route::put('/movement/{id}', [MovementController::class, 'update']);
