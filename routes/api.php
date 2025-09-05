<?php

use App\Http\Controllers\AppMovil\DatabaseController;
use App\Http\Controllers\AppMovil\MovementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/auth', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/database', [DatabaseController::class, 'index']);
Route::post('/movement', [MovementController::class, 'store']);
Route::put('/movement', [MovementController::class, 'update']);
