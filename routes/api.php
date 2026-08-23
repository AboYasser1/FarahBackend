<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Current authenticated user
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Home / public data
Route::get('/cities', [CityController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

// Register
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::post('/login', [AuthController::class, 'apilogin']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

// Resend email verification
Route::post(
    '/resend-verification-email',
    [AuthController::class, 'resendVerificationEmail']
);


/*
|--------------------------------------------------------------------------
| Password Reset
|--------------------------------------------------------------------------
*/

// Forgot password
Route::post(
    '/forgot-password',
    [AuthController::class, 'forgotPassword']
);

// Reset password
Route::post(
    '/reset-password',
    [AuthController::class, 'resetPassword']
);


/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Locations
    Route::apiResource('locations', LocationController::class);

    // Profile
    Route::get('/profile', [AuthController::class, 'profile']);

    Route::post(
        '/profile/update',
        [AuthController::class, 'updateProfile']
    );

    // Delete account
    Route::delete(
        '/account',
        [AuthController::class, 'deleteAccount']
    );

    // Change password
    Route::post(
        '/change-password',
        [AuthController::class, 'changePassword']
    );
});


/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/

Route::get('/users', [UserController::class, 'index']);