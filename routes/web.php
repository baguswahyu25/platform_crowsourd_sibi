<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ValidatorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIBI Dataset Crowdsourcing Platform
|--------------------------------------------------------------------------
*/

// Public & Landing Route
Route::get('/', function () {
    return view('pages.landing');
})->name('landing');

// Guest & Authentication Routes
Route::controller(AuthController::class)->name('auth.')->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.store');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.store');
    Route::get('/forgot-password', 'showForgotPassword')->name('forgot-password');
    Route::post('/logout', 'logout')->name('logout');
});

// Contributor Routes
Route::prefix('contributor')->name('contributor.')->group(function () {
    Route::get('/dashboard', [ContributorController::class, 'dashboard'])->name('dashboard');
    Route::get('/dataset', [ContributorController::class, 'datasets'])->name('dataset.index');
    Route::get('/upload', [ContributorController::class, 'upload'])->name('dataset.upload');
    Route::post('/upload', [DatasetController::class, 'store'])->name('dataset.store');
    Route::get('/ai-check/{id}', [DatasetController::class, 'aiCheck'])->name('dataset.ai_check');
    Route::get('/kebutuhan', [ContributorController::class, 'kebutuhan'])->name('kebutuhan.index');
});

// Validator Routes
Route::prefix('validator')->name('validator.')->group(function () {
    Route::get('/dashboard', [ValidatorController::class, 'dashboard'])->name('dashboard');
    Route::get('/antrean', [ValidatorController::class, 'antrean'])->name('antrean');
    Route::get('/detail/{id}', [ValidatorController::class, 'detail'])->name('detail');
    Route::post('/detail/{id}/process', [ValidatorController::class, 'processValidation'])->name('process');
    Route::get('/status', [ValidatorController::class, 'status'])->name('status');
    Route::get('/riwayat', [ValidatorController::class, 'riwayat'])->name('riwayat');
});

// Administrator Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/kebutuhan', [AdminController::class, 'kebutuhan'])->name('kebutuhan.index');
    Route::get('/pengguna', [AdminController::class, 'pengguna'])->name('pengguna.index');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan.index');
});

// Dataset & General Profile Routes
Route::get('/dataset/{dataset}/validation-result', [DatasetController::class, 'validationResult'])->name('dataset.validation-result');
Route::get('/dataset/{id}', [DatasetController::class, 'detail'])->name('dataset.detail');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.index');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
