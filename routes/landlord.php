<?php

use App\Http\Controllers\Landlord\TenantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'role:superadmin'])
    ->prefix('landlord')
    ->name('landlord.')
    ->group(function () {
        Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/{tenant}', [TenantController::class, 'show'])->name('tenants.show');
        Route::post('/tenants/{tenant}/suspend', [TenantController::class, 'suspend'])->name('tenants.suspend');
        Route::post('/tenants/{tenant}/activate', [TenantController::class, 'activate'])->name('tenants.activate');
    });
