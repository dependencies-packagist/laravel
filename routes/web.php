<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/up', \App\Http\Controllers\HealthController::class)->name('health');
