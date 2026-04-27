<?php

use App\Http\Controllers\Admin\ProduitController as AdminProduitController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\ProduitsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProduitsController::class, 'index'])->name('catalogue');
Route::get('/catalogue', [ProduitsController::class, 'index'])->name('catalogue.index');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

    Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
    Route::post('/panier/{produit}', [PanierController::class, 'add'])->name('panier.add');
    Route::patch('/panier/{produit}', [PanierController::class, 'update'])->name('panier.update');
    Route::delete('/panier/{produit}', [PanierController::class, 'remove'])->name('panier.remove');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/produits', [AdminProduitController::class, 'index'])->name('produits.index');
    Route::get('/produits/create', [AdminProduitController::class, 'create'])->name('produits.create');
    Route::post('/produits', [AdminProduitController::class, 'store'])->name('produits.store');
    Route::get('/produits/{produit}/edit', [AdminProduitController::class, 'edit'])->name('produits.edit');
    Route::put('/produits/{produit}', [AdminProduitController::class, 'update'])->name('produits.update');
    Route::delete('/produits/{produit}', [AdminProduitController::class, 'destroy'])->name('produits.destroy');
});
