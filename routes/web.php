<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MaleController;
use App\Http\Controllers\FemelleController;
use App\Http\Controllers\SaillieController;
use App\Http\Controllers\MiseBasController;
use App\Http\Controllers\NaissanceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\EmailVerificationCodeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome page (Login/Register)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Dashboard (protected)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Email Verification Code Routes
Route::middleware('auth')->group(function () {
    Route::post('/verification/send', [EmailVerificationCodeController::class, 'sendCode'])->name('verification.send');
    Route::post('/verification/verify', [EmailVerificationCodeController::class, 'verify'])->name('verification.verify');
    Route::post('/verification/resend', [EmailVerificationCodeController::class, 'resend'])->name('verification.resend');
});

// Males Management
Route::middleware(['auth'])->prefix('males')->name('males.')->group(function () {
    Route::get('/', [MaleController::class, 'index'])->name('index');
    Route::get('/create', [MaleController::class, 'create'])->name('create');
    Route::post('/', [MaleController::class, 'store'])->name('store');
    Route::get('/{male}/edit', [MaleController::class, 'edit'])->name('edit');
    Route::put('/{male}', [MaleController::class, 'update'])->name('update');
    Route::delete('/{male}', [MaleController::class, 'destroy'])->name('destroy');
    Route::patch('/{male}/toggle-etat', [MaleController::class, 'toggleEtat'])->name('toggleEtat');
});

// Femelles Management
Route::middleware(['auth'])->prefix('femelles')->name('femelles.')->group(function () {
    Route::get('/', [FemelleController::class, 'index'])->name('index');
    Route::get('/create', [FemelleController::class, 'create'])->name('create');
    Route::post('/', [FemelleController::class, 'store'])->name('store');
    Route::get('/{femelle}/edit', [FemelleController::class, 'edit'])->name('edit');
    Route::put('/{femelle}', [FemelleController::class, 'update'])->name('update');
    Route::delete('/{femelle}', [FemelleController::class, 'destroy'])->name('destroy');
    Route::patch('/{femelle}/toggle-etat', [FemelleController::class, 'toggleEtat'])->name('toggleEtat');
});

// Saillies Management
Route::middleware(['auth'])->prefix('saillies')->name('saillies.')->group(function () {
    Route::get('/', [SaillieController::class, 'index'])->name('index');
    Route::get('/create', [SaillieController::class, 'create'])->name('create');
    Route::post('/', [SaillieController::class, 'store'])->name('store');
    Route::get('/{saillie}/edit', [SaillieController::class, 'edit'])->name('edit');
    Route::put('/{saillie}', [SaillieController::class, 'update'])->name('update');
    Route::delete('/{saillie}', [SaillieController::class, 'destroy'])->name('destroy');
});

// Mises Bas Management
Route::middleware(['auth'])->prefix('mises-bas')->name('mises-bas.')->group(function () {
    Route::get('/', [MiseBasController::class, 'index'])->name('index');
    Route::get('/create', [MiseBasController::class, 'create'])->name('create');
    Route::post('/', [MiseBasController::class, 'store'])->name('store');
    Route::get('/{miseBas}', [MiseBasController::class, 'show'])->name('show');
    Route::get('/{miseBas}/edit', [MiseBasController::class, 'edit'])->name('edit');
    Route::put('/{miseBas}', [MiseBasController::class, 'update'])->name('update');
    Route::delete('/{miseBas}', [MiseBasController::class, 'destroy'])->name('destroy');
});

// Naissances Management
Route::middleware(['auth'])->prefix('naissances')->name('naissances.')->group(function () {
    Route::get('/', [NaissanceController::class, 'index'])->name('index');
    Route::get('/create', [NaissanceController::class, 'create'])->name('create');
    Route::post('/', [NaissanceController::class, 'store'])->name('store');
    Route::get('/{naissance}', [NaissanceController::class, 'show'])->name('show');
    Route::get('/{naissance}/edit', [NaissanceController::class, 'edit'])->name('edit');
    Route::put('/{naissance}', [NaissanceController::class, 'update'])->name('update');
    Route::delete('/{naissance}', [NaissanceController::class, 'destroy'])->name('destroy');
});

// Settings
Route::middleware(['auth'])->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::post('/', [SettingsController::class, 'update'])->name('update');
    Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('profile');
    Route::get('/export', [SettingsController::class, 'exportData'])->name('export');
    Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('clear-cache');
});

// Auth Routes (Breeze)
require __DIR__.'/auth.php';