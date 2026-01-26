<?php

namespace App\Providers;

// LAS IMPORTACIONES VAN AQUÍ ARRIBA (FUERA DE LA CLASE)
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Definimos quién es SuperAdmin
        Gate::define('admin-only', function ($user) {
            // Verificamos que el usuario tenga un rol y que el nombre sea SuperAdmin
            return $user->rol && $user->rol->nombre === 'SuperAdmin';
        });
    }
}
