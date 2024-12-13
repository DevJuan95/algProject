<?php

namespace App\Providers;

use App\Contracts\Marketplace;
use App\Services\Marketplace\AlegraIngredientBuyer;
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
        $this->app->bind(
            Marketplace::class,
            AlegraIngredientBuyer::class
        );
    }
}
