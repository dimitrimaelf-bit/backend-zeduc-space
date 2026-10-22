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
use App\Http\Controllers\Etudiant\AccueilController;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\Etudiant\ParrainageController;
use App\Http\Controllers\Etudiant\PaiementController;


use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ClaimController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\DashboardController;

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
    Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // Si c'est un étudiant, on l'envoie sur l'accueil étudiant
    if ($user->role && $user->role->code === 'student') {
        return redirect()->route('etudiant.accueil');
    }
    
    // Si c'est un admin, on l'envoie sur le tableau de bord admin
    if ($user->role && $user->role->code === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // Par défaut (si autre rôle), on peut le renvoyer vers l'accueil étudiant pour l'instant
    return redirect()->route('etudiant.accueil');
})->name('dashboard');
});

// ESPACE ÉTUDIANT 

// Enregistrement du consentement des cookies
    Route::post('/cookies', [CookieController::class, 'store'])->name('cookies.store');
// Routes de retour CinetPay (GET et POST car CinetPay renvoie parfois en POST sur le return_url)
    Route::match(['get', 'post'], '/paiement/retour', [PaiementController::class,     'retourPaiement'])->name('cinetpay.return');
    Route::post('/paiement/notify', [PaiementController::class, 'notify'])->name('cinetpay.notify');

Route::middleware(['auth'])->prefix('etudiant')->name('etudiant.')->group(function () {

    // Page d'accueil avec le Top 10 et les points
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

    // --- RÉCLAMATIONS ---
    Route::get('/reclamations', [ReclamationController::class, 'index'])->name('reclamations.index');
    Route::get('/reclamations/nouveau', [ReclamationController::class, 'create'])->name('reclamations.create');
    Route::post('/reclamations/nouveau', [ReclamationController::class, 'store'])->name('reclamations.store');
        // --- PARRAINAGE ---
    // --- PARRAINAGE ---
    Route::post('/parrainage/generer', [ParrainageController::class, 'generer'])->name('parrainage.generer');
    Route::post('/parrainage/utiliser', [ParrainageController::class, 'utiliser'])->name('parrainage.utiliser');
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
