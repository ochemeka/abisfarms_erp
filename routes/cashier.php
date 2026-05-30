<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cashier\TillSessionController;
use App\Http\Controllers\Cashier\DashboardController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\POS\SaleController;
use App\Http\Controllers\Cashier\RefundController as CashierRefundController;
use App\Http\Controllers\Owner\InvoiceController;

// this is  cashiers routes 


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Till sessions
Route::get('/till', [TillSessionController::class, 'index'])
    ->name('till.index');
Route::post('/till/open', [TillSessionController::class, 'open'])
    ->name('till.open');
Route::get('/till/close', [TillSessionController::class, 'closeForm'])
    ->name('till.close.form');
Route::post('/till/close', [TillSessionController::class, 'close'])
    ->name('till.close');
Route::get('/till/{tillSession}/reconcile',
    [TillSessionController::class, 'reconcile'])
    ->name('till.reconcile');
Route::post('/till/{tillSession}/approve',
    [TillSessionController::class, 'approve'])
    ->name('till.approve');


// Cashiers can also access POS
Route::get('/sell', [POSController::class, 'index'])
    ->name('sell');
Route::post('/sale', [SaleController::class, 'store'])
    ->name('sale.store');
Route::get('/receipt/{sale}', [SaleController::class, 'receipt'])
    ->name('receipt');

// Refunds
Route::get('/refunds', [CashierRefundController::class, 'index'])
    ->name('refunds.index');
Route::get('/refunds/create/{sale}', [CashierRefundController::class, 'create'])
    ->name('refunds.create');
Route::post('/refunds', [CashierRefundController::class, 'store'])
    ->name('refunds.store');



// Cashiers can create invoices
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



// thermal printing for cashiers
Route::get('invoices/{invoice}/thermal',
    [InvoiceController::class, 'thermal'])
    ->name('invoices.thermal'); 
    
 