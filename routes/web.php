<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Tenant\BillingController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\SettingsController;
use Illuminate\Support\Facades\Route;

// ── Central domain: registration / marketing ─────────────────────────────────
Route::middleware(['web'])->group(function () {
    Route::get('/', fn () => view('welcome'))->name('home');
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// ── Tenant subdomain routes ───────────────────────────────────────────────────
Route::middleware(['web', 'tenant', 'auth'])
    ->name('tenant.')
    ->group(function () {

        // Dashboard — only accessible to subscribed/trial tenants
        Route::get('/dashboard', DashboardController::class)
            ->middleware('subscribed')
            ->name('dashboard');

        // Billing — always accessible so lapsed tenants can resubscribe
        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/', [BillingController::class, 'index'])->name('index');
            Route::post('/subscribe', [BillingController::class, 'subscribe'])->name('subscribe');
            Route::post('/cancel', [BillingController::class, 'cancel'])->name('cancel');
            Route::get('/portal', [BillingController::class, 'portal'])->name('portal');
        });

        // Settings — owner/admin only
        Route::prefix('settings')->name('settings.')->middleware('role:owner,admin')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::patch('/', [SettingsController::class, 'update'])->name('update');
        });
    });

// ── Landlord (super-admin) routes ─────────────────────────────────────────────
require __DIR__ . '/landlord.php';
