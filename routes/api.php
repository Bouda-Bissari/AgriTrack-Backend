<?php

use App\Http\Controllers\Api\InterventionController;
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
    /**
     * Lands Endpoints.
     */
    Route::post('/lands', [LandController::class, 'store']);
    Route::get('/lands', [LandController::class, 'index']);
    Route::get('/lands/{id}', [LandController::class, 'show']);
    Route::delete('/lands/{id}', [LandController::class, 'destroy']);
    Route::put('/lands/{id}', [LandController::class, 'update']);

    /**
     * Interventions Endpoints.
     */
    Route::post('/intervention', [InterventionController::class, 'store']);
    Route::get('/myinterventions', [InterventionController::class, 'getInterventionsByUser']);
    Route::get('/interventionstodo', [InterventionController::class, 'index']);
    Route::put('/intervention/{id}', [InterventionController::class, 'update']);
    Route::delete('/intervention/{id}', [InterventionController::class, 'destroy']);
    Route::get('/intervention/{id}', [InterventionController::class, 'show']);
});
