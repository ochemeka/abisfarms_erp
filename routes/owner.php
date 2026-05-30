<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ShopController;
use App\Http\Controllers\Owner\UserController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\Owner\DepartmentController;
use App\Http\Controllers\Owner\ShopSwitchController;
use App\Http\Controllers\Owner\StockController;
use App\Http\Controllers\Owner\InvoiceController;
use App\Http\Controllers\Owner\ShopSettingsController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\POS\SaleController;
use App\Http\Controllers\Owner\ExpenseController;
use App\Http\Controllers\Owner\ImportExportController;

use App\Http\Controllers\Owner\SupplierController;
use App\Http\Controllers\Owner\PriceController;


// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Shops
Route::resource('shops', ShopController::class);
Route::patch('shops/{shop}/toggle', [ShopController::class, 'toggle'])
    ->name('shops.toggle');

// Users
Route::resource('users', UserController::class)
    ->except(['show', 'destroy']);
Route::patch('users/{user}/suspend', [UserController::class, 'suspend'])
    ->name('users.suspend');
Route::patch('users/{user}/restore', [UserController::class, 'restore'])
    ->name('users.restore');

// Inventory
Route::resource('categories', CategoryController::class)
    ->except(['show']);
Route::resource('products', ProductController::class)
    ->except(['show']);
Route::patch('products/{product}/toggle', [ProductController::class, 'toggle'])
    ->name('products.toggle');

// Departments
Route::get('/departments', [DepartmentController::class, 'index'])
    ->name('departments.index');
Route::post('/departments', [DepartmentController::class, 'store'])
    ->name('departments.store');
Route::put('/departments/{department}', [DepartmentController::class, 'update'])
    ->name('departments.update');
Route::patch('/departments/{department}/toggle', [DepartmentController::class, 'toggle'])
    ->name('departments.toggle');
Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])
    ->name('departments.destroy');

// Shop overview & context switching
Route::get('/shops-overview', [ShopSwitchController::class, 'overview'])
    ->name('shops.overview');
Route::get('/shops/{shop}/manage', [ShopSwitchController::class, 'show'])
    ->name('shops.manage');
Route::post('/shops/{shop}/switch', [ShopSwitchController::class, 'switch'])
    ->name('shops.switch');
Route::post('/shops/clear-context', [ShopSwitchController::class, 'clearContext'])
    ->name('shops.clear');

// Stock management
Route::get('/stock', [StockController::class, 'index'])
    ->name('stock.index');
Route::post('/stock/adjust', [StockController::class, 'adjust'])
    ->name('stock.adjust');
Route::get('/stock/{product}/history', [StockController::class, 'history'])
    ->name('stock.history');

// Owner can also operate POS directly
Route::get('/sell', [POSController::class, 'index'])
    ->name('sell');
Route::post('/sale', [SaleController::class, 'store'])
    ->name('sale.store');
Route::get('/receipt/{sale}', [SaleController::class, 'receipt'])
    ->name('receipt');

// Invoices
Route::resource('invoices', InvoiceController::class)
    ->except(['edit', 'update']);
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])
    ->name('invoices.pdf');
Route::post('invoices/{invoice}/mark-sent', [InvoiceController::class, 'markSent'])
    ->name('invoices.mark-sent');
Route::post('invoices/{invoice}/payment', [InvoiceController::class, 'recordPayment'])
    ->name('invoices.payment');
Route::post('invoices/from-sale/{sale}', [InvoiceController::class, 'fromSale'])
    ->name('invoices.from-sale');
Route::get('invoices/{invoice}/thermal', [InvoiceController::class, 'thermal'])
    ->name('invoices.thermal');

// Shop settings (branding + logo)
Route::get('settings', [ShopSettingsController::class, 'edit'])
    ->name('settings.edit');
Route::post('settings', [ShopSettingsController::class, 'update'])
    ->name('settings.update');

// Expenses
Route::resource('expenses', ExpenseController::class);
Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])
    ->name('expenses.approve');
Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject'])
    ->name('expenses.reject');

    // ── Suppliers ─────────────────────────────────────────────────────────────────
Route::resource('suppliers', SupplierController::class)->except(['show']);
Route::patch('suppliers/{supplier}/toggle', [SupplierController::class, 'toggle'])
    ->name('suppliers.toggle');

// ── Price Management ──────────────────────────────────────────────────────────
Route::get('prices', [PriceController::class, 'index'])->name('prices.index');
Route::post('prices/bulk-update', [PriceController::class, 'bulkUpdate'])->name('prices.bulk-update');
Route::patch('prices/{product}', [PriceController::class, 'updatePrice'])->name('prices.update');


// Import & Export
Route::prefix('import-export')->group(function () {
    Route::get('/',                   [ImportExportController::class, 'index'])->name('import.index');
    Route::post('/import/products',   [ImportExportController::class, 'importProducts'])->name('import.products');
    Route::post('/import/customers',  [ImportExportController::class, 'importCustomers'])->name('import.customers');
    Route::post('/import/suppliers',  [ImportExportController::class, 'importSuppliers'])->name('import.suppliers');
    Route::post('/import/sales',      [ImportExportController::class, 'importSalesHistory'])->name('import.sales');
    Route::get('/export/products',    [ImportExportController::class, 'exportProducts'])->name('export.products');
    Route::get('/export/customers',   [ImportExportController::class, 'exportCustomers'])->name('export.customers');
    Route::get('/export/suppliers',   [ImportExportController::class, 'exportSuppliers'])->name('export.suppliers');
    Route::get('/export/sales',       [ImportExportController::class, 'exportSalesHistory'])->name('export.sales');
    Route::get('/template/products',  [ImportExportController::class, 'templateProducts'])->name('template.products');
    Route::get('/template/customers', [ImportExportController::class, 'templateCustomers'])->name('template.customers');
    Route::get('/template/suppliers', [ImportExportController::class, 'templateSuppliers'])->name('template.suppliers');
    Route::get('/template/sales',     [ImportExportController::class, 'templateSales'])->name('template.sales');
});