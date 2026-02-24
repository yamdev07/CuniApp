<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes (Breeze)
|--------------------------------------------------------------------------
| Note: Registration & Login routes are now in routes/web.php 
| for custom verification flow
*/

// ==================== GUEST ROUTES ====================
Route::middleware('guest')->group(function () {
    // ❌ Registration moved to web.php for custom verification flow
    // Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    // Route::post('register', [RegisteredUserController::class, 'store']);
    
    // ❌ Login moved to web.php for welcome page integration
    // Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    // Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    // ✅ Password Reset (keep these)
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// ==================== AUTHENTICATED ROUTES ====================
Route::middleware('auth')->group(function () {
    // ❌ Default Breeze verification not used (we use code-based)
    // Route::get('verify-email', EmailVerificationPromptController::class)
    //     ->name('verification.notice');
    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    //     ->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');
    // Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    //     ->middleware('throttle:6,1')
    //     ->name('verification.send');
    
    // ✅ Password Confirmation (keep for sensitive actions)
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // ✅ Password Update (keep for profile settings)
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // ✅ Logout (keep)
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});