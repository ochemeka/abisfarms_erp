<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\ExpenseController;
use App\Http\Controllers\Manager\TillSessionController as ManagerTillController;
use App\Http\Controllers\Owner\InvoiceController;
use App\Http\Controllers\Supervisor\RefundController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\POS\SaleController;


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Refunds
Route::get('/refunds', [RefundController::class, 'index'])
    ->name('refunds.index');
Route::post('/refunds/{refundRequest}/approve',
    [RefundController::class, 'approve'])
    ->name('refunds.approve');
Route::post('/refunds/{refundRequest}/reject',
    [RefundController::class, 'reject'])
    ->name('refunds.reject');

// Expenses (view + approve/reject only)
Route::get('/expenses', [ExpenseController::class, 'index'])
    ->name('expenses.index');
Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])
    ->name('expenses.show');
Route::post('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])
    ->name('expenses.approve');
Route::post('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])
    ->name('expenses.reject');

// Invoices
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

// Till

Route::get('/till', [ManagerTillController::class, 'index'])
    ->name('till.index');
Route::post('/till/open', [ManagerTillController::class, 'open'])
    ->name('till.open');
Route::get('/till/close', [ManagerTillController::class, 'closeForm'])
    ->name('till.close.form');
Route::post('/till/close', [ManagerTillController::class, 'close'])
    ->name('till.close');
Route::get('/till/{tillSession}/reconcile', [ManagerTillController::class, 'reconcile'])
    ->name('till.reconcile');
Route::post('/till/{tillSession}/approve', [ManagerTillController::class, 'approve'])
    ->name('till.approve');

// & POS
// Route::get('/till', [TillSessionController::class, 'index'])
//     ->name('till.index');
// Route::post('/till/open', [TillSessionController::class, 'open'])
//     ->name('till.open');
// Route::get('/till/close', [TillSessionController::class, 'closeForm'])
//     ->name('till.close.form');
// Route::post('/till/close', [TillSessionController::class, 'close'])
//     ->name('till.close');
Route::get('/sell', [POSController::class, 'index'])
    ->name('sell');
Route::post('/sale', [SaleController::class, 'store'])
    ->name('sale.store');
Route::get('/receipt/{sale}', [SaleController::class, 'receipt'])
    ->name('receipt');