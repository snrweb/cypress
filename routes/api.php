<?php

// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authenticate the super admin and generate an access token
Route::post('/admin/login', [AuthController::class, 'login']);

// Register user
Route::post('/admin/register', [AdminController::class, 'storeUser']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Fetch the list of all registered users
    Route::get('/admin/users', [AdminController::class, 'getUsers']);

    // Fetch the list of all global activities
    Route::get('/admin/activities', [ActivityController::class, 'index']);

    // Fetch the list of activities between date
    Route::get('/admin/activities/{from}/{to}', [ActivityController::class, 'getActivitiesByDate']);

    // Create a new global activity
    Route::post('/admin/activities', [ActivityController::class, 'store']);

    // Update an existing global activity
    Route::patch('/admin/activities/{activity}', [ActivityController::class, 'update']);

    // Delete an existing global activity
    Route::delete('/admin/activities/{activity}', [ActivityController::class, 'destroy']);

    // Fetch the list of activities for a specific user
    Route::get('/admin/users/{user}/activities', [AdminController::class, 'getUserActivities']);

    // Create a new activity for a specific user
    Route::post('/admin/users/{user}/activities', [AdminController::class, 'storeUserActivity']);

    // Update an activity for a specific user
    Route::put('/admin/users/{user}/activities/{activity}', [AdminController::class, 'updateUserActivity']);

    // Delete an activity for a specific user
    Route::delete('/admin/users/{user}/activities/{activity}', [AdminController::class, 'destroyUserActivity']);

    Route::post('/admin/user/logout', [AuthController::class, 'logout']);
});
