<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ApiResposneProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // API Success Response
        Response::macro('success', function ($message = '', $data, $code = 200) {
            return Response::json([
                'code' => $code,
                'status' => 'success',
                'message' => $message,
                'data' => $data
            ]);
        });

        // API Message Response
        Response::macro('message', function ($message, $code = 200) {
            return Response::json([
                'code' => $code,
                'status' => 'success',
                'message' => $message
            ]);
        });

        // Api Error Response
        Response::macro('error', function ($message, $code = 500) {
            return Response::json([
                'code' => $code,
                'status' => 'error',
                'message' => $message
            ]);
        });
    }
}
