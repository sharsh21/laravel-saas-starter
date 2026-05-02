<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant\BillingController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\SettingsController;
use Illuminate\Support\Facades\Route;

// ── Central domain ────────────────────────────────────────────────────────────
Route::get('/', fn () => view('welcome'))->name('home');

// ── Tenant subdomain routes ───────────────────────────────────────────────────
Route::middleware(['tenant', 'auth'])
    ->name('tenant.')
    ->group(function () {

        Route::get('/dashboard', DashboardController::class)
            ->middleware('subscribed')
            ->name('dashboard');

        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/', [BillingController::class, 'index'])->name('index');
            Route::post('/subscribe', [BillingController::class, 'subscribe'])->name('subscribe');
            Route::post('/cancel', [BillingController::class, 'cancel'])->name('cancel');
            Route::get('/portal', [BillingController::class, 'portal'])->name('portal');
        });

        Route::prefix('settings')->name('settings.')->middleware('role:owner,admin')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::patch('/', [SettingsController::class, 'update'])->name('update');
        });

        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });
    });

// ── Landlord routes ───────────────────────────────────────────────────────────
require __DIR__ . '/landlord.php';

// ── Breeze auth routes (login, register, password reset, email verify) ────────
require __DIR__ . '/auth.php';
