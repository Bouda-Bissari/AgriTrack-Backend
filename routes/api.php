<?php

use App\Http\Controllers\Api\LandController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/lands', [LandController::class, 'store']);
    Route::get('/lands', [LandController::class, 'index']);
    Route::get('/lands/{id}', [LandController::class, 'show']);
    Route::delete('/lands/{id}', [LandController::class, 'destroy']);
    Route::post('/lands/{id}', [LandController::class, 'update']);
});
