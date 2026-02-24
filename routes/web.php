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

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
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

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/verify-email', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    
    Route::post('/email/verification-notification', function () {
        auth()->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');
    
    // Custom Verification Code Routes
    Route::post('/verification/verify', [EmailVerificationCodeController::class, 'verify'])
        ->name('verification.verify');
    Route::post('/verification/resend', [EmailVerificationCodeController::class, 'resend'])
        ->name('verification.resend');
    Route::post('/verification/send-code', [EmailVerificationCodeController::class, 'sendCode'])
        ->name('verification.send-code');
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Mâles (Resource)
    Route::get('/males', [MaleController::class, 'index'])->name('males.index');
    Route::get('/males/create', [MaleController::class, 'create'])->name('males.create');
    Route::post('/males', [MaleController::class, 'store'])->name('males.store');
    Route::get('/males/{male}', [MaleController::class, 'show'])->name('males.show');
    Route::get('/males/{male}/edit', [MaleController::class, 'edit'])->name('males.edit');
    Route::put('/males/{male}', [MaleController::class, 'update'])->name('males.update');
    Route::delete('/males/{male}', [MaleController::class, 'destroy'])->name('males.destroy');
    Route::patch('/males/{male}/toggle-etat', [MaleController::class, 'toggleEtat'])->name('males.toggleEtat');
    
    // Femelles (Resource)
    Route::get('/femelles', [FemelleController::class, 'index'])->name('femelles.index');
    Route::get('/femelles/create', [FemelleController::class, 'create'])->name('femelles.create');
    Route::post('/femelles', [FemelleController::class, 'store'])->name('femelles.store');
    Route::get('/femelles/{femelle}', [FemelleController::class, 'show'])->name('femelles.show');
    Route::get('/femelles/{femelle}/edit', [FemelleController::class, 'edit'])->name('femelles.edit');
    Route::put('/femelles/{femelle}', [FemelleController::class, 'update'])->name('femelles.update');
    Route::delete('/femelles/{femelle}', [FemelleController::class, 'destroy'])->name('femelles.destroy');
    Route::patch('/femelles/{femelle}/toggle-etat', [FemelleController::class, 'toggleEtat'])->name('femelles.toggleEtat');
    
    // Saillies (Resource)
    Route::get('/saillies', [SaillieController::class, 'index'])->name('saillies.index');
    Route::get('/saillies/create', [SaillieController::class, 'create'])->name('saillies.create');
    Route::post('/saillies', [SaillieController::class, 'store'])->name('saillies.store');
    Route::get('/saillies/{saillie}', [SaillieController::class, 'show'])->name('saillies.show');
    Route::get('/saillies/{saillie}/edit', [SaillieController::class, 'edit'])->name('saillies.edit');
    Route::put('/saillies/{saillie}', [SaillieController::class, 'update'])->name('saillies.update');
    Route::delete('/saillies/{saillie}', [SaillieController::class, 'destroy'])->name('saillies.destroy');
    
    // Mises Bas (Resource)
    Route::get('/mises-bas', [MiseBasController::class, 'index'])->name('mises-bas.index');
    Route::get('/mises-bas/create', [MiseBasController::class, 'create'])->name('mises-bas.create');
    Route::post('/mises-bas', [MiseBasController::class, 'store'])->name('mises-bas.store');
    Route::get('/mises-bas/{miseBas}', [MiseBasController::class, 'show'])->name('mises-bas.show');
    Route::get('/mises-bas/{miseBas}/edit', [MiseBasController::class, 'edit'])->name('mises-bas.edit');
    Route::put('/mises-bas/{miseBas}', [MiseBasController::class, 'update'])->name('mises-bas.update');
    Route::delete('/mises-bas/{miseBas}', [MiseBasController::class, 'destroy'])->name('mises-bas.destroy');
    
    // Naissances (Resource)
    Route::get('/naissances', [NaissanceController::class, 'index'])->name('naissances.index');
    Route::get('/naissances/create', [NaissanceController::class, 'create'])->name('naissances.create');
    Route::post('/naissances', [NaissanceController::class, 'store'])->name('naissances.store');
    Route::get('/naissances/{naissance}', [NaissanceController::class, 'show'])->name('naissances.show');
    Route::get('/naissances/{naissance}/edit', [NaissanceController::class, 'edit'])->name('naissances.edit');
    Route::put('/naissances/{naissance}', [NaissanceController::class, 'update'])->name('naissances.update');
    Route::delete('/naissances/{naissance}', [NaissanceController::class, 'destroy'])->name('naissances.destroy');
    
    // Lapins (Generic - for the "Nouvelle entrée" button)
    Route::get('/lapins/create', [LapinController::class, 'create'])->name('lapins.create');
    Route::post('/lapins', [LapinController::class, 'store'])->name('lapins.store');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::get('/settings/export', [SettingsController::class, 'exportData'])->name('settings.export');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// Password Confirmation
Route::middleware('auth')->group(function () {
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);
});