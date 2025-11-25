<?php

use App\Http\Controllers\ApiIntegrationController;
use App\Http\Controllers\AppMovil\DatabaseController;
use App\Http\Controllers\AppMovil\MovementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ControllerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoorController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\MovementController as ControllersMovementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/database', [DatabaseController::class, 'index']);
Route::post('/auth', [AuthController::class, 'login']);

Route::middleware('auth:locker')->group(function () {
    Route::get('/get-token', [DatabaseController::class, 'getToken']);
    Route::get('/database', [DatabaseController::class, 'dataBase']);
    Route::post('/movement/pending', [MovementController::class, 'pending']);
    Route::post('/movement/received', [MovementController::class, 'received']);
});

Route::post('/create-token', [DatabaseController::class, 'createToken']);

Route::middleware('auth:api')->group(function () {

    Route::get('/auth', [AuthController::class, 'getUser']);
    Route::prefix('dashboard')->group(function () {
        Route::get('/info', [DashboardController::class, 'info'])->name('configurations.dashboard.info');
    });

    Route::prefix('locker')->group(function () {
        Route::get('/requirement', [LockerController::class, 'requirement'])->name('configurations.locker.requirement');
        Route::post('/data-table', [LockerController::class, 'dataTable'])->name('configurations.locker.data-table');
        Route::post('/get-status', [LockerController::class, 'getStatus'])->name('configurations.locker.get-status');
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
        Route::get('/open/{id}', [DoorController::class, 'open'])->name('configurations.door.open');
        Route::post('', [DoorController::class, 'store'])->name('configurations.door.store');
        Route::get('{id}', [DoorController::class, 'edit'])->name('configurations.door.edit');
        Route::put('{id}', [DoorController::class, 'update'])->name('configurations.door.update');
        Route::delete('{id}', [DoorController::class, 'destroy'])->name('configurations.door.destroy');
    });

    Route::prefix('movement')->group(function () {
        Route::post('/data-table', [ControllersMovementController::class, 'dataTable'])->name('configurations.locker.data-table');
        Route::post('/get-status', [LockerController::class, 'getStatus'])->name('configurations.locker.get-status');
        Route::post('/detailed-movement', [LockerController::class, 'detailed_movement'])->name('configurations.locker.get-status');
    });

    Route::prefix('building')->group(function () {
        Route::post('/dashboard-locker', [BuildingController::class, 'dashBoardLocker'])->name('configurations.dashboard.info');
        Route::get('/dashboard-building', [BuildingController::class, 'dashBoardBuilding'])->name('configurations.building.data-table');
        Route::post('', [BuildingController::class, 'store'])->name('configurations.building.store');
        Route::get('{id}', [BuildingController::class, 'edit'])->name('configurations.building.edit');
        Route::put('{id}', [BuildingController::class, 'update'])->name('configurations.building.update');
        Route::delete('{id}', [BuildingController::class, 'destroy'])->name('configurations.building.destroy');
    });

    Route::prefix('api-integration')->group(function () {
        Route::post('/data-table', [ApiIntegrationController::class, 'dataTable'])->name('configurations.api-integration.data-table');
        Route::post('', [ApiIntegrationController::class, 'store'])->name('configurations.api-integration.store');
        Route::get('{id}', [ApiIntegrationController::class, 'edit'])->name('configurations.api-integration.edit');
        Route::put('{id}', [ApiIntegrationController::class, 'update'])->name('configurations.api-integration.update');
        Route::delete('{id}', [ApiIntegrationController::class, 'destroy'])->name('configurations.api-integration.destroy');
    });

    Route::prefix('department')->group(function () {
        Route::post('/data-table', [DepartmentController::class, 'dataTable'])->name('configurations.user.data-table');
        Route::get('/requeriment', [DepartmentController::class, 'requeriment'])->name('configurations.user.requeriment');
        Route::post('', [DepartmentController::class, 'store'])->name('configurations.user.store');
        Route::get('{id}', [DepartmentController::class, 'edit'])->name('configurations.user.edit');
        Route::put('{id}', [DepartmentController::class, 'update'])->name('configurations.user.update');
        Route::delete('{id}', [DepartmentController::class, 'destroy'])->name('configurations.user.destroy');
    });

    Route::prefix('user-deparment')->group(function () {
        Route::post('/data-table', [UserController::class, 'dataTable'])->name('configurations.user.data-table');
    });
    
});
