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


 

 
Route::middleware(['auth:sanctum'])->group(function () {
 
    // Products list for invoice builder & sell page offline cache
    Route::get('/offline/products', function () {
        $shopId = session('active_shop_id') ?? auth()->user()->shop_id;
        $products = \App\Models\Product::where('shop_id', $shopId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'price', 'unit', 'stock_quantity', 'track_stock']);
 
        return response()->json($products);
    });
 
    // Customers list for invoice builder offline cache
    Route::get('/offline/customers', function () {
        $shopId = session('active_shop_id') ?? auth()->user()->shop_id;
        $customers = \App\Models\Customer::where('shop_id', $shopId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'email', 'address']);
 
        return response()->json($customers);
    });
 
    // Sync pending sales (called by SW background sync)
    Route::post('/offline/sync-sales',    [OfflineSyncController::class, 'syncSales']);
 
    // Sync pending invoices (called by SW background sync)
    Route::post('/offline/sync-invoices', [OfflineSyncController::class, 'syncInvoices']);
 
});