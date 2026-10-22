<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Etudiant\Cookies as CookieConsentController;
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
use App\Http\Controllers\Admin\LoyaltyController as AdminLoyaltyController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PaymentController;

Route::get('/', function () {
    $settings = \App\Models\AppSetting::whereIn('setting_key', [
        'privacy_policy_url',
        'terms_of_service_url',
        'cookie_policy_version',
    ])->pluck('setting_value', 'setting_key');

    return view('home', [
        'privacyPolicyUrl' => $settings['privacy_policy_url'] ?? null,
        'termsOfServiceUrl' => $settings['terms_of_service_url'] ?? null,
        'cookiePolicyVersion' => $settings['cookie_policy_version'] ?? '1.0',
    ]);
})->name('home');
Route::post('/cookies/consent', [CookieConsentController::class, 'store'])->name('cookies.consent');

// 1. ROUTES ACCESSIBLES UNIQUEMENT QUAND ON N'EST PAS CONNECTÉ
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

// 2. ROUTES ACCESSIBLES UNIQUEMENT QUAND ON EST CONNECTÉ
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ESPACE ÉTUDIANT
    Route::prefix('etudiant')->name('etudiant.')->group(function () {
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

    // ESPACE ADMINISTRATION (RÉSERVÉ AUX ADMINISTRATEURS : role_id = 4)
    Route::middleware('admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            
            // Route du Tableau de Bord Administration (/admin/dashboard)
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
            Route::post('/loyalty/adjust', [AdminLoyaltyController::class, 'adjust'])->name('loyalty.adjust');
            Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
            Route::post('/payments/{payment}/confirm-cash', [PaymentController::class, 'confirmCash'])->name('payments.confirm-cash');
            
            // Routes CRUD des modules d'administration
            Route::resource('employees', EmployeeController::class);
            Route::resource('categories', MenuCategoryController::class);
            Route::resource('menu', MenuItemController::class);
            Route::resource('promotions', PromotionController::class);
            Route::resource('events', EventController::class)->except(['show']);
            Route::resource('claims', ClaimController::class)->only(['index', 'edit', 'update']);
            Route::resource('settings', AppSettingController::class)->only(['index', 'update']);
            
        });

});