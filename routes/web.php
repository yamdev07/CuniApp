<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationCodeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FemelleController;
use App\Http\Controllers\MaleController;
use App\Http\Controllers\SaillieController;
use App\Http\Controllers\MiseBasController;
use App\Http\Controllers\NaissanceController;
use App\Http\Controllers\LapinController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Welcome route - MUST be first to avoid conflicts
Route::get('/', function () {
    // Force authenticated users to dashboard, guests stay on welcome
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

// Registration flow (custom controller)
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
Route::post('/verification/code/send', [EmailVerificationCodeController::class, 'sendCode'])->name('verification.code.send');
Route::post('/verification/code/verify', [EmailVerificationCodeController::class, 'verify'])->name('verification.code.verify');
Route::post('/verification/code/resend', [EmailVerificationCodeController::class, 'resend'])->name('verification.code.resend');

// Authentication routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Password reset routes (keep default Laravel structure)
Route::get('/forgot-password', [AuthenticatedSessionController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [AuthenticatedSessionController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [AuthenticatedSessionController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [AuthenticatedSessionController::class, 'store'])->name('password.update');

// Authenticated routes group
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Resource routes
    Route::resource('males', MaleController::class);
    Route::resource('femelles', FemelleController::class);
    Route::resource('saillies', SaillieController::class);
    Route::resource('mises-bas', MiseBasController::class)->parameters(['mises-bas' => 'miseBas']);
    Route::resource('naissances', NaissanceController::class);
    Route::resource('lapins', LapinController::class)->except(['show']);
    
    // Custom routes
    Route::patch('males/{male}/toggle-etat', [MaleController::class, 'toggleEtat'])->name('males.toggleEtat');
    Route::patch('femelles/{femelle}/toggle-etat', [FemelleController::class, 'toggleEtat'])->name('femelles.toggleEtat');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::get('/settings/export', [SettingsController::class, 'exportData'])->name('settings.export');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');

    // Profile
// Dans routes/web.php
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
