<?php

use App\Http\Controllers\AppMovil\DatabaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/auth', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/database', [DatabaseController::class, 'index']);
