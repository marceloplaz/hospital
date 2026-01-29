<?php

namespace App\Providers;

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
    // 1. Gate original
    Gate::define('admin-only', function ($user) {
        return $user->rol && $user->rol->nombre === 'SuperAdmin';
    });

    // 2. Gate Universal mejorada
    Gate::before(function ($user, $ability) {
        // Verificamos que el usuario tenga un rol asignado antes de preguntar
        if (!$user->rol) {
            return null; // No decide nada, deja que otros Gates lo intenten
        }

        // Si es SuperAdmin, tiene permiso total
        if ($user->rol->nombre === 'SuperAdmin') {
            return true;
        }

        // Verificamos si la relación permisos existe para evitar errores de "null"
        if ($user->rol->relationLoaded('permisos') || $user->rol->permisos) {
            return $user->rol->permisos->contains('nombre', $ability);
        }
    });
}
}