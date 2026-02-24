<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationCodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaleController;
use App\Http\Controllers\FemelleController;
use App\Http\Controllers\SaillieController;
use App\Http\Controllers\MiseBasController;
use App\Http\Controllers\NaissanceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LapinController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome route - public homepage with login/register forms
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes (Guest middleware - only accessible when not logged in)
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    // Registration routes with email verification flow
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    // Password reset routes
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    
    // ✅ CRITICAL FIX: Email verification code routes (was missing causing RouteNotFoundException)
    Route::post('/verification/code/verify', [EmailVerificationCodeController::class, 'verify'])
        ->name('verification.code.verify');
    
    Route::post('/verification/code/resend', [EmailVerificationCodeController::class, 'resend'])
        ->name('verification.code.resend');
    
    Route::post('/verification/code/send', [EmailVerificationCodeController::class, 'sendCode'])
        ->name('verification.code.send');
});

// Logout route (available to authenticated users)
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Protected routes (require authentication + email verification)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Males resource routes
    Route::resource('males', MaleController::class)->except(['show']);
    
    // Femelles resource routes
    Route::resource('femelles', FemelleController::class)->except(['show']);
    
    // Saillies resource routes
    Route::resource('saillies', SaillieController::class);
    
    // Mises Bas resource routes (with dash in URL)
    Route::resource('mises-bas', MiseBasController::class)
        ->parameters(['mises-bas' => 'miseBas'])
        ->names([
            'index' => 'mises-bas.index',
            'create' => 'mises-bas.create',
            'store' => 'mises-bas.store',
            'show' => 'mises-bas.show',
            'edit' => 'mises-bas.edit',
            'update' => 'mises-bas.update',
            'destroy' => 'mises-bas.destroy',
        ]);
    
    // Naissances resource routes
    Route::resource('naissances', NaissanceController::class);
    
    // Lapins unified route (for combined male/female creation)
    Route::resource('lapins', LapinController::class)->only(['create', 'store']);
    
    // Settings routes
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::get('settings/export', [SettingsController::class, 'exportData'])->name('settings.export');
    Route::post('settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    
    // State toggle routes (AJAX-friendly)
    Route::patch('males/{male}/toggle-etat', [MaleController::class, 'toggleEtat'])->name('males.toggleEtat');
    Route::patch('femelles/{femelle}/toggle-etat', [FemelleController::class, 'toggleEtat'])->name('femelles.toggleEtat');
});

// Fallback route for undefined pages (optional but recommended)
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Health check route (for monitoring)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
        'environment' => config('app.env'),
        'timestamp' => now()->toIso8601String(),
    ]);
});