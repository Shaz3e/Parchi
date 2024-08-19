<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('app.domain'))->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
});
