<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OfflineSyncController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/offline/sync-sales',    [OfflineSyncController::class, 'syncSales']);
    Route::post('/offline/sync-invoices', [OfflineSyncController::class, 'syncInvoices']);
    Route::get('/offline/products',       [OfflineSyncController::class, 'products']);
});