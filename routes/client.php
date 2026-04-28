<?php

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CatalogueController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogueController::class, 'index'])->name('catalogue');
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue.index');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('auth')->group(function () {
    Route::get('/panier', [CartController::class, 'index'])->name('panier.index');
    Route::post('/panier/{produit}', [CartController::class, 'add'])->name('panier.add');
    Route::patch('/panier/{produit}', [CartController::class, 'update'])->name('panier.update');
    Route::delete('/panier/{produit}', [CartController::class, 'remove'])->name('panier.remove');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});
