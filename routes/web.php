<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\HomeController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Etudiant\CartController;
use App\Http\Controllers\Etudiant\LoyaltyController;
use App\Http\Controllers\Etudiant\MenuController;
use App\Http\Controllers\Etudiant\OrderController;
use App\Http\Controllers\Etudiant\ReclamationController;
use App\Http\Controllers\Gerant\ClaimReviewController;
use App\Http\Controllers\Gerant\EmployeeAccountController;
use App\Http\Controllers\Gerant\OrderSupervisionController;
use App\Http\Controllers\Gerant\StatisticsController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\ClaimController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PromotionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::post('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::middleware('role:3')->prefix('gerant')->name('gerant.')->group(function () {
        Route::get('/commandes', [OrderSupervisionController::class, 'index'])
            ->name('orders.index');
        Route::patch('/commandes/{order}/status', [OrderSupervisionController::class, 'updateStatus'])
            ->name('orders.status.update');
        Route::get('/employes', [EmployeeAccountController::class, 'index'])
            ->name('employees.index');
        Route::post('/employes', [EmployeeAccountController::class, 'store'])
            ->name('employees.store');
        Route::get('/statistiques', [StatisticsController::class, 'index'])
            ->name('statistics.index');
        Route::get('/reclamations', [ClaimReviewController::class, 'index'])
            ->name('claims.index');
        Route::patch('/reclamations/{claim}/review', [ClaimReviewController::class, 'review'])
            ->name('claims.review');
    });

    Route::middleware('role:3')->group(function () {
        Route::get('/Gerant/Supervision/', fn () => redirect()->route('gerant.orders.index'))
            ->name('gerant.legacy.supervision');
        Route::get('/Gerant/Supervision/Orderspending', fn () => redirect()->route('gerant.orders.index', ['status' => 'preparing']))
            ->name('gerant.legacy.orders.pending');
        Route::get('/Gerant/Supervision/Ordersready', fn () => redirect()->route('gerant.orders.index', ['status' => 'ready']))
            ->name('gerant.legacy.orders.ready');
        Route::get('/Gerant/Supervision/Ordersdelivered', fn () => redirect()->route('gerant.orders.index', ['status' => 'delivered']))
            ->name('gerant.legacy.orders.delivered');
        Route::get('/Gerant/Claims', fn () => redirect()->route('gerant.claims.index'))
            ->name('gerant.legacy.claims');
        Route::get('/Gerant/Employeeaccounts', fn () => redirect()->route('gerant.employees.index'))
            ->name('gerant.legacy.employees');
        Route::get('/Gerant/Stats', fn () => redirect()->route('gerant.statistics.index'))
            ->name('gerant.legacy.statistics');
    });

    Route::middleware('role:2')->prefix('etudiant')->name('etudiant.')->group(function () {
        Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
        Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
        Route::post('/panier/ajouter', [CartController::class, 'add'])->name('cart.add');
        Route::post('/panier/retirer', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/commande', [OrderController::class, 'store'])->name('order.store');
        Route::get('/fidelite', [LoyaltyController::class, 'index'])->name('loyalty.index');
        Route::get('/reclamations', [ReclamationController::class, 'index'])->name('reclamations.index');
        Route::get('/reclamations/nouveau', [ReclamationController::class, 'create'])->name('reclamations.create');
        Route::post('/reclamations/nouveau', [ReclamationController::class, 'store'])->name('reclamations.store');
    });

    Route::middleware('role:4')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'stats'])->name('dashboard');
        Route::resource('employees', EmployeeController::class);
        Route::resource('categories', MenuCategoryController::class);
        Route::resource('menu', MenuItemController::class);
        Route::resource('promotions', PromotionController::class);
        Route::resource('claims', ClaimController::class)->only(['index', 'edit', 'update']);
        Route::resource('settings', AppSettingController::class)->only(['index', 'update']);
    });
});