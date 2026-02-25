<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;

// Welcome/Auth routes - ALL point to welcome page
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('welcome');
Route::get('/welcome', [AuthenticatedSessionController::class, 'create'])->name('welcome');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');

// Auth actions
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout'); // ✅ Critical: Named route

// Password reset routes (optional but recommended)
Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.store');

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Your resource routes here...
    Route::resource('males', App\Http\Controllers\MaleController::class);
    Route::resource('femelles', App\Http\Controllers\FemelleController::class);
    Route::resource('saillies', App\Http\Controllers\SaillieController::class);
    Route::resource('mises-bas', App\Http\Controllers\MiseBasController::class);
    Route::resource('naissances', App\Http\Controllers\NaissanceController::class);
    Route::resource('lapins', App\Http\Controllers\LapinController::class);
    
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::get('/settings/export', [App\Http\Controllers\SettingsController::class, 'exportData'])->name('settings.export');
    Route::post('/settings/clear-cache', [App\Http\Controllers\SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    
    // Toggle states
    Route::patch('/femelles/{femelle}/toggle-etat', [App\Http\Controllers\FemelleController::class, 'toggleEtat'])->name('femelles.toggleEtat');
    Route::patch('/males/{male}/toggle-etat', [App\Http\Controllers\MaleController::class, 'toggleEtat'])->name('males.toggleEtat');
});