<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaleController;
use App\Http\Controllers\FemelleController;
use App\Http\Controllers\SaillieController;
use App\Http\Controllers\MiseBasController;
use App\Http\Controllers\LapinController;
use App\Http\Controllers\NaissanceController;
use App\Http\Controllers\SettingsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Tableau de bord
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// CRUD Mâles
Route::resource('males', MaleController::class);
Route::patch('males/{male}/toggle-etat', [MaleController::class, 'toggleEtat'])->name('males.toggleEtat');

// CRUD Femelles
Route::resource('femelles', FemelleController::class);
Route::patch('femelles/{femelle}/etat', [FemelleController::class, 'toggleEtat'])->name('femelles.toggleEtat');

// CRUD Saillies
Route::resource('saillies', SaillieController::class)->parameters([
    'saillies' => 'saillie'
]);


// CRUD Mises Bas 
Route::resource('mises-bas', MiseBasController::class)
    ->parameters(['mises-bas' => 'mises_ba']);
    
// Lapin – création et stockage
Route::get('/lapin/create', [LapinController::class, 'create'])->name('lapin.create');
Route::post('/lapins', [LapinController::class, 'store'])->name('lapins.store');


// Contrôleur Naissance 
Route::resource('naissances', NaissanceController::class);


// Routes pour les paramètres
Route::prefix('parametres')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::post('/save', [SettingsController::class, 'save'])->name('save');
    Route::post('/save-general', [SettingsController::class, 'saveGeneral'])->name('save.general');
    Route::post('/save-elevage', [SettingsController::class, 'saveElevage'])->name('save.elevage');
    Route::post('/save-notifications', [SettingsController::class, 'saveNotifications'])->name('save.notifications');
    Route::post('/save-appearance', [SettingsController::class, 'saveAppearance'])->name('save.appearance');
    Route::post('/export-data', [SettingsController::class, 'exportData'])->name('export');
    Route::post('/reset-data', [SettingsController::class, 'resetData'])->name('reset');
});