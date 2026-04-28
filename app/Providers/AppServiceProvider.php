<?php

namespace App\Providers;

use App\Models\Paiement;
use App\Models\Produits;
use App\Observers\PaiementObserver;
use App\Observers\ProduitsObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Produits::observe(ProduitsObserver::class);
        Paiement::observe(PaiementObserver::class);
    }
}
