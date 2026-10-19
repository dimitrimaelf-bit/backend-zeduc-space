<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Gerant\EmployeeAccountController;
use App\Http\Controllers\Gerant\ClaimReviewController;
use App\Http\Controllers\Gerant\OrderSupervisionController;
use App\Http\Controllers\Gerant\StatisticsController;

// routes accessibles uniquement quand on est pas connecté
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});


// routes accessibles uniquement quand on est connecté
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::post('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/gerant/commandes', [OrderSupervisionController::class, 'index'])
        ->name('gerant.orders.index');
    Route::patch('/gerant/commandes/{order}/status', [OrderSupervisionController::class, 'updateStatus'])
        ->name('gerant.orders.status.update');
    Route::post('/gerant/employes', [EmployeeAccountController::class, 'store'])
        ->name('gerant.employees.store');
    Route::patch('/gerant/employes/{employee}/access', [EmployeeAccountController::class, 'updateAccess'])
        ->name('gerant.employees.access.update');
    Route::get('/gerant/statistiques', [StatisticsController::class, 'index'])
        ->name('gerant.statistics.index');
    Route::get('/gerant/reclamations', [ClaimReviewController::class, 'index'])
        ->name('gerant.claims.index');
    Route::patch('/gerant/reclamations/{claim}/review', [ClaimReviewController::class, 'review'])
        ->name('gerant.claims.review');
});