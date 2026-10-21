<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Etudiant\MenuController;
use App\Http\Controllers\Etudiant\CartController;
use App\Http\Controllers\Etudiant\OrderController;
use App\Http\Controllers\Etudiant\LoyaltyController;
use App\Http\Controllers\Etudiant\ReclamationController;

use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ClaimController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Employee\EmployeeOrderController;
use App\Http\Controllers\Employee\EmployeeMenuController;
use App\Http\Controllers\Employee\EmployeeReclamationController;
use App\Http\Controllers\Employee\EmployeeStatController;

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
});

// ESPACE ÉTUDIANT 

Route::middleware(['auth', 'role:student'])->prefix('etudiant')->name('etudiant.')->group(function () {
    
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

    // --- RÉCLAMATIONS ---
    Route::get('/reclamations', [ReclamationController::class, 'index'])->name('reclamations.index');
    Route::get('/reclamations/nouveau', [ReclamationController::class, 'create'])->name('reclamations.create');
    Route::post('/reclamations/nouveau', [ReclamationController::class, 'store'])->name('reclamations.store');

});

// ROUTES ADMINISTRATION (SECURISEES PAR MIDDLEWARE )
Route::middleware(['auth', 'role:admin']) //Securite : Seul un admin connecte peut entrer
    ->prefix('admin')                    //URL : Ajoute /admin devant chaque route
    ->name('admin.')                     //Nommage : Ajoute admin. devant le nom des routes
    ->group(function () {
        
        // Route du Tableau de Bord (accessible sur /admin/dashboard)
        Route::get('/dashboard', [DashboardController::class, 'stats'])->name('dashboard');
        
        // Routes CRUD des modules d'administration
        Route::resource('employees', EmployeeController::class);
        Route::resource('categories', MenuCategoryController::class);
        Route::resource('menu', MenuItemController::class);
        Route::resource('promotions', PromotionController::class);
        Route::resource('claims', ClaimController::class)->only(['index', 'edit', 'update']);
        Route::resource('settings', AppSettingController::class)->only(['index', 'update']);
        
    });

Route::middleware(['auth:sanctum', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {
        Route::get('/orders', [EmployeeOrderController::class, 'index']);
        Route::get('/orders/{id}', [EmployeeOrderController::class, 'show']);
        Route::patch('/orders/{id}/validate', [EmployeeOrderController::class, 'validateOrder']);
        Route::patch('/orders/{id}/served', [EmployeeOrderController::class, 'markAsServed']);

        Route::get('/menu', [EmployeeMenuController::class, 'index']);
        Route::patch('/menu/{id}/availability', [EmployeeMenuController::class, 'updateAvailability']);
        Route::patch('/menu/{id}/plat-du-jour', [EmployeeMenuController::class, 'updatePlatDuJour']);

        Route::get('/reclamations', [EmployeeReclamationController::class, 'index']);
        Route::get('/reclamations/{id}', [EmployeeReclamationController::class, 'show']);
        Route::post('/reclamations/{id}/reply', [EmployeeReclamationController::class, 'replyToReclamation']);

        Route::get('/stats/weekly', [EmployeeStatController::class, 'weeklyStats']);
    });