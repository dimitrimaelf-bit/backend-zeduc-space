<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Etudiant\AccueilController;
use App\Http\Controllers\Etudiant\CookieController;
use App\Http\Controllers\Etudiant\MenuController;
use App\Http\Controllers\Etudiant\CartController;
use App\Http\Controllers\Etudiant\OrderController;
use App\Http\Controllers\Etudiant\LoyaltyController;
use App\Http\Controllers\Etudiant\ParrainageController;
use App\Http\Controllers\Etudiant\ReclamationController;

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
    Route::get('/dashboard', fn () => redirect()->route('etudiant.accueil'))->name('dashboard'); #changement de requette : post en get 
});

// Gestion de l'acceptation des cookies
Route::post('/cookies', [CookieController::class, 'store'])->name('cookies.store');

// ESPACE ÉTUDIANT 

Route::middleware(['auth'])->prefix('etudiant')->name('etudiant.')->group(function () {

    // Accueil étudiant
    Route::get('/accueil', [AccueilController::class, 'index'])->name('accueil');
    
    // Affichage du Menu
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    
    // Gestion du Panier
    Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
    Route::post('/panier/ajouter', [CartController::class, 'add'])->name('cart.add');
    Route::post('/panier/retirer', [CartController::class, 'remove'])->name('cart.remove');
    
    // Gestion des Commandes
    Route::post('/commande', [OrderController::class, 'store'])->name('order.store');

    // --- FIDÉLITÉ ---
    Route::get('/fidelite', [LoyaltyController::class, 'index'])->name('loyalty.index');
    Route::post('/parrainage/generer', [ParrainageController::class, 'generer'])->name('parrainage.generer');
    Route::post('/parrainage/utiliser', [ParrainageController::class, 'utiliser'])->name    ('parrainage.utiliser');

    // --- FIDÉLITÉ & PARRAINAGE ---
    Route::get('/reclamations', [ReclamationController::class, 'index'])->name('reclamations.index');
    Route::get('/reclamations/nouveau', [ReclamationController::class, 'create'])->name('reclamations.create');
    Route::post('/reclamations/nouveau', [ReclamationController::class, 'store'])->name('reclamations.store');

    

});