<?php

use Illuminate\Support\Facades\Route;

// Root
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes
require __DIR__.'/auth.php';

// Authenticated routes
Route::middleware(['auth', 'active'])->group(function () {

    // Default redirect after login
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $role = $user->roles->first()?->name ?? 'owner';
        return match($role) {
            'site-admin'    => redirect()->route('admin.dashboard'),
            'owner'         => redirect()->route('owner.dashboard'),
            'manager'       => redirect()->route('manager.dashboard'),
            'hr'            => redirect()->route('hr.dashboard'),
            'supervisor'    => redirect()->route('supervisor.dashboard'),
            'cashier'       => redirect()->route('cashier.dashboard'),
            'pos-attendant' => redirect()->route('pos.dashboard'),
            default         => redirect()->route('owner.dashboard'),
        };
    })->name('dashboard');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // ── Site Admin — NO has_shop (admin manages all shops)
    Route::middleware(['role:site-admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(base_path('routes/admin.php'));

    // ── Owner
    Route::middleware(['role:owner', 'has_shop'])
        ->prefix('owner')
        ->name('owner.')
        ->group(base_path('routes/owner.php'));

    // ── Manager
    Route::middleware(['role:manager', 'has_shop'])
        ->prefix('manager')
        ->name('manager.')
        ->group(base_path('routes/manager.php'));

    // ── HR
    Route::middleware(['role:hr', 'has_shop'])
        ->prefix('hr')
        ->name('hr.')
        ->group(base_path('routes/hr.php'));

    // ── Supervisor
    Route::middleware(['role:supervisor', 'has_shop'])
        ->prefix('supervisor')
        ->name('supervisor.')
        ->group(base_path('routes/supervisor.php'));

    // ── Cashier
    Route::middleware(['role:cashier', 'has_shop'])
        ->prefix('cashier')
        ->name('cashier.')
        ->group(base_path('routes/cashier.php'));

    // ── POS Attendant
    Route::middleware(['role:pos-attendant', 'has_shop'])
        ->prefix('pos')
        ->name('pos.')
        ->group(base_path('routes/pos.php'));

});