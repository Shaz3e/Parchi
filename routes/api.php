<?php

use App\Http\Controllers\Api\V1\Auth\ChangePasswordController;
use App\Http\Controllers\Api\V1\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function () {
    return 'user route';
})->middleware('auth:sanctum');

Route::domain(config('app.domain'))->group(function () {

    // Register
    Route::post('/register', RegisterController::class);

    // Login
    Route::post('/login', LoginController::class);

    // Forget Password
    Route::post('/forget-password', ForgetPasswordController::class);

    // Reset Password
    Route::get('/reset-password/{email}/{token}', ResetPasswordController::class);

    Route::middleware('auth:sanctum')->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return 'main url for owner';
        });

        // Change Password
        Route::post('/change-password', ChangePasswordController::class);

        // Logout
        Route::post('/logout', LogoutController::class);
    });
});
