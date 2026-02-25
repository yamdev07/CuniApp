<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaleController;
use App\Http\Controllers\FemelleController;
use App\Http\Controllers\SaillieController;
use App\Http\Controllers\MiseBasController;
use App\Http\Controllers\NaissanceController;
use App\Http\Controllers\LapinController; // ← Add this
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Lapins (generic entry point)
    Route::get('/lapins/create', [LapinController::class, 'create'])->name('lapins.create');
    Route::post('/lapins', [LapinController::class, 'store'])->name('lapins.store');
    Route::get('/lapins', [LapinController::class, 'index'])->name('lapins.index');
    Route::get('/lapins/{lapin}/edit', [LapinController::class, 'edit'])->name('lapins.edit');
    Route::put('/lapins/{lapin}', [LapinController::class, 'update'])->name('lapins.update');
    Route::delete('/lapins/{lapin}', [LapinController::class, 'destroy'])->name('lapins.destroy');
    Route::patch('/lapins/{lapin}/toggle-etat', [LapinController::class, 'toggleEtat'])->name('lapins.toggleEtat');


    // Mâles
    Route::get('/males', [MaleController::class, 'index'])->name('males.index');
    Route::get('/males/create', [MaleController::class, 'create'])->name('males.create');
    Route::post('/males', [MaleController::class, 'store'])->name('males.store');
    Route::get('/males/{male}/edit', [MaleController::class, 'edit'])->name('males.edit');
    Route::put('/males/{male}', [MaleController::class, 'update'])->name('males.update');
    Route::delete('/males/{male}', [MaleController::class, 'destroy'])->name('males.destroy');
    Route::patch('/males/{male}/toggle-etat', [MaleController::class, 'toggleEtat'])->name('males.toggleEtat');

    // Femelles
    Route::get('/femelles', [FemelleController::class, 'index'])->name('femelles.index');
    Route::get('/femelles/create', [FemelleController::class, 'create'])->name('femelles.create');
    Route::post('/femelles', [FemelleController::class, 'store'])->name('femelles.store');
    Route::get('/femelles/{femelle}/edit', [FemelleController::class, 'edit'])->name('femelles.edit');
    Route::put('/femelles/{femelle}', [FemelleController::class, 'update'])->name('femelles.update');
    Route::delete('/femelles/{femelle}', [FemelleController::class, 'destroy'])->name('femelles.destroy');
    Route::patch('/femelles/{femelle}/toggle-etat', [FemelleController::class, 'toggleEtat'])->name('femelles.toggleEtat');

    // Saillies
    Route::resource('saillies', SaillieController::class);

    // Mises Bas
    Route::resource('mises-bas', MiseBasController::class);

    // Naissances
    Route::resource('naissances', NaissanceController::class);

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