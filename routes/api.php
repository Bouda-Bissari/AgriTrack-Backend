<?php

use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\Api\InterventionController;
use App\Http\Controllers\Api\LandController;
use App\Http\Controllers\api\UserStatsController;
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
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/users/{user}/stats', [UserStatsController::class, 'getUserStats']);
    Route::get('/users/{user}/intervention-stats', [UserStatsController::class, 'getUserInterventionStats']);
    Route::get('/users/{user}/monthly-stats', [UserStatsController::class, 'getUserMonthlyStats']);
    Route::get('/users/{user}/culture-stats', [UserStatsController::class, 'getUserCultureStats']);

    // Lands Endpoints
    Route::post('/lands', [LandController::class, 'store']);
    Route::get('/lands', [LandController::class, 'index']);
    Route::get('/lands/{id}', [LandController::class, 'show']);
    Route::post('/lands/{id}', [LandController::class, 'update']);
    Route::delete('/lands/{id}', [LandController::class, 'destroy']);

    // Interventions Endpoints
    Route::post('/intervention', [InterventionController::class, 'store']);
    Route::get('/myinterventions', [InterventionController::class, 'getInterventionsByUser']);
    Route::get('/interventions/land/{landId}', [InterventionController::class, 'getInterventionsByLand']);
    Route::post('/update/status/{id}', [InterventionController::class, 'updateStatus']);
    Route::get('/interventionstodo', [InterventionController::class, 'index']);
    Route::get('/intervention/{id}', [InterventionController::class, 'show']);
    Route::post('/intervention/{id}', [InterventionController::class, 'update']);
    Route::delete('/intervention/{id}', [InterventionController::class, 'destroy']);

    //admin endpoints
    Route::get('/admin/dashboard-stats', [AdminController::class, 'getDashboardStats']);
    Route::get('/admin/users', [AdminController::class, 'getAllUsers']);
    Route::get('/admin/user-land-stats', [AdminController::class, 'getUserLandStats']);
    Route::get('/admin/intervention-stats', [AdminController::class, 'getInterventionStats']);


    // Admin Endpoints

    Route::post('/create', [AdminController::class, 'createAdmin']);
    Route::delete('/delete/user/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/block/{id}', [AdminController::class, 'toggleBlockUser']);
});
