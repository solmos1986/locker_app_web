<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangeCompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'verificate'])->name('login.verificate');

Route::middleware(['auth'])->group(function () {

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('movement')->group(function () {
        Route::get('', function () {
            return view('pages.movements.index');
        })->name('movement.index');
    });

    Route::prefix('door')->group(function () {
        Route::get('', function () {
            return view('pages.movements.index');
        })->name('door.index');
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('', [DashboardController::class, 'index'])->name('dashboard.index');
    });

    Route::prefix('change-company')->group(function () {
        Route::get('', [ChangeCompanyController::class, 'index'])->name('change-company.index');
        Route::post('', [ChangeCompanyController::class, 'change'])->name('change-company.change');
    });
});
