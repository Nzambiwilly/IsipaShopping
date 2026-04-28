<?php

use App\Http\Controllers\Admin\ProduitController as AdminProduitController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::redirect('/', '/admin/produits')->name('dashboard');
        Route::get('/produits', [AdminProduitController::class, 'index'])->name('produits.index');
        Route::get('/produits/create', [AdminProduitController::class, 'create'])->name('produits.create');
        Route::post('/produits', [AdminProduitController::class, 'store'])->name('produits.store');
        Route::get('/produits/{produit}/edit', [AdminProduitController::class, 'edit'])->name('produits.edit');
        Route::put('/produits/{produit}', [AdminProduitController::class, 'update'])->name('produits.update');
        Route::delete('/produits/{produit}', [AdminProduitController::class, 'destroy'])->name('produits.destroy');

        Route::middleware('permission:manage_users')->group(function () {
            Route::get('/utilisateurs', [AdminUserController::class, 'index'])->name('users.index');
        });

        Route::middleware('permission:can_create_user')->group(function () {
            Route::get('/utilisateurs/create', [AdminUserController::class, 'create'])->name('users.create');
            Route::post('/utilisateurs', [AdminUserController::class, 'store'])->name('users.store');
        });

        Route::middleware('permission:can_edit_user')->group(function () {
            Route::get('/utilisateurs/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
            Route::put('/utilisateurs/{user}', [AdminUserController::class, 'update'])->name('users.update');
        });

        Route::middleware('permission:can_delete_user')->group(function () {
            Route::delete('/utilisateurs/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        });
    });
