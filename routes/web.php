<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaleController;
use App\Http\Controllers\FemelleController;
use App\Http\Controllers\SaillieController;
use App\Http\Controllers\MiseBasController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LapinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationCodeController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;

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

// ========================================================================
// PUBLIC ROUTES (No authentication required)
// ========================================================================

Route::get('/', function () {
    return redirect()->route('welcome');
});

Route::get('/welcome', [AuthenticatedSessionController::class, 'create'])
    ->name('welcome');

// ========================================================================
// GUEST ROUTES (Only accessible to unauthenticated users)
// ========================================================================

Route::middleware('guest')->group(function () {
    // Authentication Routes
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // ✅ CRITICAL FIX #1: Custom verification code routes MUST be in guest middleware
    // (Users aren't authenticated yet during email verification flow)
    Route::post('/verification/code/verify', [EmailVerificationCodeController::class, 'verify'])
        ->name('verification.code.verify'); // Matches welcome.blade.php form action

    Route::post('/verification/code/resend', [EmailVerificationCodeController::class, 'resend'])
        ->name('verification.code.resend'); // Fixes "Route not defined" error

    Route::post('/verification/send-code', [EmailVerificationCodeController::class, 'sendCode'])
        ->name('verification.send-code');
});

// ========================================================================
// AUTHENTICATED ROUTES (Require login but NOT email verification)
// ========================================================================

Route::middleware('auth')->group(function () {
    // Standard Laravel Email Verification
    Route::get('/verify-email', fn() => view('auth.verify-email'))
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // ✅ CRITICAL FIX #2: Fixed auth()->user() call with proper null check
    Route::post('/email/verification-notification', function () {
        if (auth()->check() && auth()->user()) {
            auth()->user()->sendEmailVerificationNotification();
            return back()->with('status', 'verification-link-sent');
        }
        return redirect()->route('login');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Password Confirmation
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Logout (available to authenticated users)
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// ========================================================================
// FULLY VERIFIED ROUTES (Require login AND email verification)
// ========================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Mâles CRUD (Resource-style with explicit routes)
    Route::get('/males', [MaleController::class, 'index'])
        ->name('males.index');
    Route::get('/males/create', [MaleController::class, 'create'])
        ->name('males.create');
    Route::post('/males', [MaleController::class, 'store'])
        ->name('males.store');
    Route::get('/males/{male}', [MaleController::class, 'show'])
        ->name('males.show');
    Route::get('/males/{male}/edit', [MaleController::class, 'edit'])
        ->name('males.edit');
    Route::put('/males/{male}', [MaleController::class, 'update'])
        ->name('males.update');
    Route::delete('/males/{male}', [MaleController::class, 'destroy'])
        ->name('males.destroy');
    Route::patch('/males/{male}/toggle-etat', [MaleController::class, 'toggleEtat'])
        ->name('males.toggleEtat');

    // Femelles CRUD (Resource-style with explicit routes)
    Route::get('/femelles', [FemelleController::class, 'index'])
        ->name('femelles.index');
    Route::get('/femelles/create', [FemelleController::class, 'create'])
        ->name('femelles.create');
    Route::post('/femelles', [FemelleController::class, 'store'])
        ->name('femelles.store');
    Route::get('/femelles/{femelle}', [FemelleController::class, 'show'])
        ->name('femelles.show');
    Route::get('/femelles/{femelle}/edit', [FemelleController::class, 'edit'])
        ->name('femelles.edit');
    Route::put('/femelles/{femelle}', [FemelleController::class, 'update'])
        ->name('femelles.update');
    Route::delete('/femelles/{femelle}', [FemelleController::class, 'destroy'])
        ->name('femelles.destroy');
    Route::patch('/femelles/{femelle}/toggle-etat', [FemelleController::class, 'toggleEtat'])
        ->name('femelles.toggleEtat');

    // Saillies CRUD (Resource-style with explicit routes)
    Route::get('/saillies', [SaillieController::class, 'index'])
        ->name('saillies.index');
    Route::get('/saillies/create', [SaillieController::class, 'create'])
        ->name('saillies.create');
    Route::post('/saillies', [SaillieController::class, 'store'])
        ->name('saillies.store');
    Route::get('/saillies/{saillie}', [SaillieController::class, 'show'])
        ->name('saillies.show');
    Route::get('/saillies/{saillie}/edit', [SaillieController::class, 'edit'])
        ->name('saillies.edit');
    Route::put('/saillies/{saillie}', [SaillieController::class, 'update'])
        ->name('saillies.update');
    Route::delete('/saillies/{saillie}', [SaillieController::class, 'destroy'])
        ->name('saillies.destroy');

    // Mises Bas CRUD (Resource-style with explicit routes)
    Route::get('/mises-bas', [MiseBasController::class, 'index'])
        ->name('mises-bas.index');
    Route::get('/mises-bas/create', [MiseBasController::class, 'create'])
        ->name('mises-bas.create');
    Route::post('/mises-bas', [MiseBasController::class, 'store'])
        ->name('mises-bas.store');
    Route::get('/mises-bas/{miseBas}', [MiseBasController::class, 'show'])
        ->name('mises-bas.show');
    Route::get('/mises-bas/{miseBas}/edit', [MiseBasController::class, 'edit'])
        ->name('mises-bas.edit');
    Route::put('/mises-bas/{miseBas}', [MiseBasController::class, 'update'])
        ->name('mises-bas.update');
    Route::delete('/mises-bas/{miseBas}', [MiseBasController::class, 'destroy'])
        ->name('mises-bas.destroy');

    // Lapins (Unified entry point)
    Route::get('/lapins/create', [LapinController::class, 'create'])
        ->name('lapins.create');
    Route::post('/lapins', [LapinController::class, 'store'])
        ->name('lapins.store');
    Route::get('/lapins', [LapinController::class, 'index'])
        ->name('lapins.index');

    // Settings Management
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])
        ->name('settings.update');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])
        ->name('settings.profile');
    Route::get('/settings/export', [SettingsController::class, 'exportData'])
        ->name('settings.export');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])
        ->name('settings.clear-cache');
});


Route::get('/males/check-code', [MaleController::class, 'checkCode'])->name('males.check-code');
Route::get('/femelles/check-code', [FemelleController::class, 'checkCode'])->name('femelles.check-code');

// Add after other resource routes
Route::resource('sales', SaleController::class)->middleware('auth');
Route::patch('sales/{sale}/mark-paid', [SaleController::class, 'markAsPaid'])->name('sales.mark-paid');

// Notification System
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

// ========================================================================
// LEGACY/BACKWARD COMPATIBILITY ROUTES
// ========================================================================

// Maintain old route aliases for migration safety
Route::redirect('/home', '/dashboard', 301);
Route::redirect('/femelles/show/{id}', '/femelles/{id}', 301);
Route::redirect('/males/show/{id}', '/males/{id}', 301);

// ========================================================================
// SYSTEM HEALTH CHECK ROUTES (Internal use)
// ========================================================================

Route::middleware('guest')->group(function () {
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'environment' => app()->environment(),
        ]);
    });

    Route::get('/ping', function () {
        return 'pong';
    });
});

// ========================================================================
// CATCH-ALL ROUTE (404 Handling)
// ========================================================================

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
})->name('fallback');
