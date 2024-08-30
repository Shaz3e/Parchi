<?php

use Illuminate\Support\Facades\Route;

// Auth Controller
use App\Http\Controllers\Api\Owner\Auth\ChangePasswordController;
use App\Http\Controllers\Api\Owner\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\Owner\Auth\LoginController;
use App\Http\Controllers\Api\Owner\Auth\LogoutController;
use App\Http\Controllers\Api\Owner\Auth\RegisterController;
use App\Http\Controllers\Api\Owner\Auth\ResetPasswordController;

// Tenant Controller
use App\Http\Controllers\Api\Owner\TenantController;


// Route::get('/user', function () {
//     return 'user route';
// })->middleware('auth:sanctum');

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

        // Tenants
        Route::resource('/tenants', TenantController::class);
        Route::put('/tenants/change-password/{tenant}', [TenantController::class, 'changePassword']);
        Route::post('/tenants/add-domain/{tenant}', [TenantController::class, 'addDomain']);
    });
});
