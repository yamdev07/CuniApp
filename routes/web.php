<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaleController;
use App\Http\Controllers\FemelleController;
use App\Http\Controllers\SaillieController;
use App\Http\Controllers\MiseBasController;
use App\Http\Controllers\NaissanceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LapinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationCodeController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return redirect()->route('welcome');
});

Route::get('/welcome', [AuthenticatedSessionController::class, 'create'])
    ->name('welcome');

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// Email Verification Routes (Auth Required)
Route::middleware('auth')->group(function () {
    // Standard Laravel verification
    Route::get('/verify-email', fn() => view('auth.verify-email'))
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', function () {
        auth()->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // ✅ CRITICAL FIX: Route names MUST match Blade templates
    Route::post('/verification/code/verify', [EmailVerificationCodeController::class, 'verify'])
        ->name('verification.code.verify'); // Matches welcome.blade.php form action

    Route::post('/verification/code/resend', [EmailVerificationCodeController::class, 'resend'])
        ->name('verification.code.resend'); // Matches welcome.blade.php JS fetch()

    Route::post('/verification/send-code', [EmailVerificationCodeController::class, 'sendCode'])
        ->name('verification.send-code');
});

// Password Confirmation Routes
Route::middleware('auth')->group(function () {
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);
});

// Fully Authenticated & Verified Routes
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

    // Mâles CRUD
    Route::resource('males', MaleController::class)->except(['show']);
    Route::patch('males/{male}/toggle-etat', [MaleController::class, 'toggleEtat'])
        ->name('males.toggleEtat');

    // Femelles CRUD
    Route::resource('femelles', FemelleController::class)->except(['show']);
    Route::patch('femelles/{femelle}/toggle-etat', [FemelleController::class, 'toggleEtat'])
        ->name('femelles.toggleEtat');

    // Saillies CRUD
    Route::resource('saillies', SaillieController::class);

    // Mises Bas CRUD
    Route::resource('mises-bas', MiseBasController::class);

    // Naissances CRUD
    Route::resource('naissances', NaissanceController::class);

    // Lapins (Unified entry point)
    Route::get('/lapins/create', [LapinController::class, 'create'])
        ->name('lapins.create');
    Route::post('/lapins', [LapinController::class, 'store'])
        ->name('lapins.store');
    Route::get('/lapins', [LapinController::class, 'index'])
        ->name('lapins.index');

    // Settings
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

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
