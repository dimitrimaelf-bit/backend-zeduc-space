<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;

use App\Http\Controllers\CookieController;
use App\Http\Controllers\Etudiant\AccueilController;
use App\Http\Controllers\Etudiant\MenuController;
use App\Http\Controllers\Etudiant\CartController;
use App\Http\Controllers\Etudiant\OrderController;
use App\Http\Controllers\Etudiant\LoyaltyController;
use App\Http\Controllers\Etudiant\ReclamationController;
use App\Http\Controllers\Etudiant\ParrainageController;
use App\Http\Controllers\Etudiant\PaiementController;
use App\Http\Controllers\Etudiant\EvenementController;

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

use App\Http\Controllers\Gerant\ClaimReviewController;
use App\Http\Controllers\Gerant\EmployeeAccountController;
use App\Http\Controllers\Gerant\OrderSupervisionController;
use App\Http\Controllers\Gerant\StatisticsController;

use App\Http\Controllers\Employee as Emp;

// PAGE D'ACCUEIL PUBLIQUE
Route::get('/', function () {
    $settings = \App\Models\AppSetting::whereIn('setting_key', [
        'privacy_policy_url',
        'terms_of_service_url',
        'cookie_policy_version',
    ])->pluck('setting_value', 'setting_key');

    return view('home', [
        'privacyPolicyUrl'    => $settings['privacy_policy_url'] ?? null,
        'termsOfServiceUrl'   => $settings['terms_of_service_url'] ?? null,
        'cookiePolicyVersion' => $settings['cookie_policy_version'] ?? '1.0',
    ]);
})->name('home');

Route::post('/cookies/consent', [CookieController::class, 'store'])->name('cookies.consent');

// Retour et notification CinetPay : hors "auth" (appel serveur à serveur, session non garantie).
// GET et POST car CinetPay renvoie parfois en POST sur le return_url.
Route::match(['get', 'post'], '/paiement/retour', [PaiementController::class, 'retourPaiement'])->name('cinetpay.return');
Route::post('/paiement/notify', [PaiementController::class, 'notify'])->name('cinetpay.notify');

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

    // Redirige chaque utilisateur vers son espace selon son rôle
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $code = $user->role?->code;

        return match (true) {
            $code === 'admin' => redirect()->route('admin.dashboard'),
            $code === 'employee' => redirect()->route('employe.dashboard'),
            (int) $user->role_id === 3 => redirect()->route('gerant.orders.index'),
            default => redirect()->route('etudiant.accueil'),
        };
    })->name('dashboard');
});

// 3. ESPACE ÉTUDIANT
Route::middleware(['auth', 'role:student'])->prefix('etudiant')->name('etudiant.')->group(function () {
    Route::get('/accueil', [AccueilController::class, 'index'])->name('accueil');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

    // Panier
    Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
    Route::post('/panier/ajouter', [CartController::class, 'add'])->name('cart.add');
    Route::post('/panier/retirer', [CartController::class, 'remove'])->name('cart.remove');

    // Commandes
    Route::post('/commande', [OrderController::class, 'store'])->name('order.store');

    // Fidélité
    Route::get('/fidelite', [LoyaltyController::class, 'index'])->name('loyalty.index');

    // Réclamations
    Route::get('/reclamations', [ReclamationController::class, 'index'])->name('reclamations.index');
    Route::get('/reclamations/nouveau', [ReclamationController::class, 'create'])->name('reclamations.create');
    Route::post('/reclamations/nouveau', [ReclamationController::class, 'store'])->name('reclamations.store');

    // Événements et promotions
    Route::get('/evenements', [EvenementController::class, 'index'])->name('evenements.index');

    // Parrainage
    Route::post('/parrainage/generer', [ParrainageController::class, 'generer'])->name('parrainage.generer');
    Route::post('/parrainage/utiliser', [ParrainageController::class, 'utiliser'])->name('parrainage.utiliser');
});

// 4. ESPACE ADMINISTRATION
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
        Route::post('/loyalty/adjust', [AdminLoyaltyController::class, 'adjust'])->name('loyalty.adjust');
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/{payment}/confirm-cash', [PaymentController::class, 'confirmCash'])->name('payments.confirm-cash');

        Route::resource('employees', EmployeeController::class);
        Route::resource('categories', MenuCategoryController::class);
        Route::resource('menu', MenuItemController::class);
        Route::resource('promotions', PromotionController::class);
        Route::resource('events', EventController::class)->except(['show']);
        Route::resource('claims', ClaimController::class)->only(['index', 'edit', 'update']);
        Route::resource('settings', AppSettingController::class)->only(['index', 'update']);
    });

// 5. ESPACE EMPLOYÉ
Route::middleware(['auth', 'role:employee'])->prefix('employe')->name('employe.')->group(function () {
    Route::get('/', [Emp\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/commandes', [Emp\OrderController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/{commande}', [Emp\OrderController::class, 'show'])->name('commandes.show');
    Route::patch('/commandes/{commande}', [Emp\OrderController::class, 'update'])->name('commandes.update');
    Route::get('/menu', [Emp\MenuController::class, 'index'])->name('menu.index');
    Route::patch('/menu/{plat}', [Emp\MenuController::class, 'update'])->name('menu.update');
    Route::get('/reclamations', [Emp\ReclamationController::class, 'index'])->name('reclamations.index');
    Route::get('/reclamations/{reclamation}', [Emp\ReclamationController::class, 'show'])->name('reclamations.show');
    Route::patch('/reclamations/{reclamation}', [Emp\ReclamationController::class, 'update'])->name('reclamations.update');
    Route::get('/statistiques', [Emp\StatController::class, 'index'])->name('statistiques');
});

// 6. ESPACE GÉRANT (role_id = 3)
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::prefix('gerant')->name('gerant.')->group(function () {
        Route::get('/commandes', [OrderSupervisionController::class, 'index'])->name('orders.index');
        Route::patch('/commandes/{order}/status', [OrderSupervisionController::class, 'updateStatus'])->name('orders.status.update');
        Route::get('/employes', [EmployeeAccountController::class, 'index'])->name('employees.index');
        Route::post('/employes', [EmployeeAccountController::class, 'store'])->name('employees.store');
        Route::get('/statistiques', [StatisticsController::class, 'index'])->name('statistics.index');
        Route::get('/reclamations', [ClaimReviewController::class, 'index'])->name('claims.index');
        Route::patch('/reclamations/{claim}/review', [ClaimReviewController::class, 'review'])->name('claims.review');
    });

    // Anciennes URL, redirigées vers les nouvelles
    Route::get('/Gerant/Supervision/', fn () => redirect()->route('gerant.orders.index'))->name('gerant.legacy.supervision');
    Route::get('/Gerant/Supervision/Orderspending', fn () => redirect()->route('gerant.orders.index', ['status' => 'preparing']))->name('gerant.legacy.orders.pending');
    Route::get('/Gerant/Supervision/Ordersready', fn () => redirect()->route('gerant.orders.index', ['status' => 'ready']))->name('gerant.legacy.orders.ready');
    Route::get('/Gerant/Supervision/Ordersdelivered', fn () => redirect()->route('gerant.orders.index', ['status' => 'delivered']))->name('gerant.legacy.orders.delivered');
    Route::get('/Gerant/Claims', fn () => redirect()->route('gerant.claims.index'))->name('gerant.legacy.claims');
    Route::get('/Gerant/Employeeaccounts', fn () => redirect()->route('gerant.employees.index'))->name('gerant.legacy.employees');
    Route::get('/Gerant/Stats', fn () => redirect()->route('gerant.statistics.index'))->name('gerant.legacy.statistics');
});