<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');