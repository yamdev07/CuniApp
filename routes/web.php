<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaleController;
use App\Http\Controllers\FemelleController;
use App\Http\Controllers\SaillieController;
use App\Http\Controllers\MiseBasController;
use App\Http\Controllers\NaissanceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Males (Reproducteurs Mâles)
    Route::resource('males', MaleController::class);
    Route::patch('males/{male}/toggle-etat', [MaleController::class, 'toggleEtat'])->name('males.toggleEtat');
    
    // Femelles (Reproductrices Femelles)
    Route::resource('femelles', FemelleController::class);
    Route::patch('femelles/{femelle}/toggle-etat', [FemelleController::class, 'toggleEtat'])->name('femelles.toggleEtat');
    
    // Saillies (Breeding)
    Route::resource('saillies', SaillieController::class);
    
    // Mises Bas (Births)
    Route::resource('mises-bas', MiseBasController::class);
    
    // Naissances (Newborns)
    Route::resource('naissances', NaissanceController::class);
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::get('settings/export', [SettingsController::class, 'exportData'])->name('settings.export');
    Route::post('settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';