<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Tenant\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\Tenant\Auth\LoginController;
use App\Http\Controllers\Api\Tenant\Auth\LogoutController;
use App\Http\Controllers\Api\Tenant\Auth\RegisterController;
use App\Http\Controllers\Api\Tenant\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Route::get('/', function () {
    //     return '...This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    // });

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
            return 'Tenant Dashboard';
        });

        // Logout
        Route::post('/logout', LogoutController::class);
    });
});
