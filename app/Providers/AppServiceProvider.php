<?php

namespace App\Providers;

use App\Models\Pedidos;
use App\Observers\PedidosObserver;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        // Get the AliasLoader instance
        $loader = AliasLoader::getInstance();
        // Add your aliases
        $loader->alias('HP', \App\Helpers\UtilHelper::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Pedidos::observe(PedidosObserver::class);
    }
}
