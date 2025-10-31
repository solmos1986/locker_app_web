<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangeCompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ResidentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'index'])->name('login');
/* Route::post('/login', [LoginController::class, 'verificate'])->name('login.verificate');

Route::middleware(['auth'])->group(function () {

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('movement')->group(function () {
        Route::get('', function () {
            return view('pages.movements.index');
        })->name('movement.index');
        Route::get('data-table', [MovementController::class, 'dataTable'])->name('movement.dataTable');
    });

    Route::prefix('locker')->group(function () {
        Route::get('', [LockerController::class, 'index'])->name('locker.index');
        Route::get('data-table', [LockerController::class, 'dataTable'])->name('locker.dataTable');
    });

    Route::prefix('door')->group(function () {
        Route::get('', function () {
            return view('pages.movements.index');
        })->name('door.index');
    });

    Route::prefix('resident')->group(function () {
        Route::get('', [ResidentController::class, 'index'])->name('resident.index');
        Route::get('data-table', [ResidentController::class, 'dataTable'])->name('resident.dataTable');
        Route::post('', [ResidentController::class, 'store'])->name('resident.store');
        Route::get('/{id}', [ResidentController::class, 'edit'])->name('resident.edit');
        Route::put('/{id}', [ResidentController::class, 'update'])->name('resident.update');
        Route::post('/{id}', [ResidentController::class, 'delete'])->name('resident.delete');
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('', [DashboardController::class, 'index'])->name('dashboard.index');
    });

    Route::prefix('change-company')->group(function () {
        Route::get('', [ChangeCompanyController::class, 'index'])->name('change-company.index');
        Route::post('', [ChangeCompanyController::class, 'change'])->name('change-company.change');
    });
});
 */