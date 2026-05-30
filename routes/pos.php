<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POS\DashboardController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\POS\SaleController;
use App\Http\Controllers\Owner\InvoiceController;
use App\Http\Controllers\Owner\ExpenseController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// POS selling screen
Route::get('/sell', [POSController::class, 'index'])
    ->name('sell');

// Process sale (AJAX)
Route::post('/sale', [SaleController::class, 'store'])
    ->name('sale.store');

// Receipt view
Route::get('/receipt/{sale}', [SaleController::class, 'receipt'])
    ->name('receipt');


// POS attendant can create invoices
Route::get('/invoices', [InvoiceController::class, 'index'])
    ->name('invoices.index');
Route::get('/invoices/create', [InvoiceController::class, 'create'])
    ->name('invoices.create');
Route::post('/invoices', [InvoiceController::class, 'store'])
    ->name('invoices.store');
Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])
    ->name('invoices.show');
Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])
    ->name('invoices.pdf');
Route::post('/invoices/{invoice}/payment',
    [InvoiceController::class, 'recordPayment'])
    ->name('invoices.payment');
Route::post('/invoices/{invoice}/mark-sent',
    [InvoiceController::class, 'markSent'])
    ->name('invoices.mark-sent');



// thermal printing for POS
Route::get('/invoices/{invoice}/thermal',
 [InvoiceController::class, 'thermal'])
    ->name('invoices.thermal');
