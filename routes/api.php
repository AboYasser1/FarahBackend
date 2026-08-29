<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ServiceController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Public Data
Route::get('/cities', [CityController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

// Services
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Authentication & Password Reset
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'apilogin'])->name('login');
Route::post('/resend-verification-email', [AuthController::class, 'resendVerificationEmail']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Password Reset
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sanctum Protected)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Current User Info
    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => true,
            'data' => $request->user()
        ]);
    });

    // Profile Management
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::delete('/account', [AuthController::class, 'deleteAccount']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Locations (CRUD)
    Route::apiResource('locations', LocationController::class);
});