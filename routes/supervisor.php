<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Supervisor\DashboardController;
use App\Http\Controllers\Supervisor\RefundController;
// use App\Http\Controllers\Cashier\TillSessionController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\POS\SaleController;
use App\Http\Controllers\Owner\InvoiceController;
use App\Http\Controllers\Supervisor\TillSessionController as SupervisorTillController;


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Supervisor approves refunds ≤ ₦5,000
Route::get('/refunds', [RefundController::class, 'index'])
    ->name('refunds.index');
Route::post('/refunds/{refundRequest}/approve',
    [RefundController::class, 'approve'])
    ->name('refunds.approve');
Route::post('/refunds/{refundRequest}/reject',
    [RefundController::class, 'reject'])
    ->name('refunds.reject');

// Supervisor can use till

Route::get('/till', [SupervisorTillController::class, 'index'])
    ->name('till.index');
Route::post('/till/open', [SupervisorTillController::class, 'open'])
    ->name('till.open');
Route::get('/till/close', [SupervisorTillController::class, 'closeForm'])
    ->name('till.close.form');
Route::post('/till/close', [SupervisorTillController::class, 'close'])
    ->name('till.close');
Route::get('/till/{tillSession}/reconcile', [SupervisorTillController::class, 'reconcile'])
    ->name('till.reconcile');
Route::post('/till/{tillSession}/approve', [SupervisorTillController::class, 'approve'])
    ->name('till.approve');

// Supervisor can use POS
Route::get('/sell', [POSController::class, 'index'])
    ->name('sell');
Route::post('/sale', [SaleController::class, 'store'])
    ->name('sale.store');
Route::get('/receipt/{sale}', [SaleController::class, 'receipt'])
    ->name('receipt');

// Supervisor can create invoices
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
Route::get('/invoices/{invoice}/thermal',
    [InvoiceController::class, 'thermal'])
    ->name('invoices.thermal');
Route::post('/invoices/{invoice}/payment',
    [InvoiceController::class, 'recordPayment'])
    ->name('invoices.payment');
Route::post('/invoices/{invoice}/mark-sent',
    [InvoiceController::class, 'markSent'])
    ->name('invoices.mark-sent');