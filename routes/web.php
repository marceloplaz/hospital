<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TurnoAsignadoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\Auth\LoginController;

// Página de inicio (Redirige al login si no hay sesión)
Route::get('/', function () {
    return Auth::check() ? redirect('/home') : redirect('/login');
});

// Rutas de Autenticación automáticas (Login, Register, Password Reset)
Auth::routes();

// Rutas protegidas (Solo usuarios logueados)
Route::middleware(['auth'])->group(function () {
    
    // LOGOUT: Forzamos que use el método del LoginController
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard (Home)
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Perfil de Usuario
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::post('/perfil/update', [PerfilController::class, 'update'])->name('perfil.update');

    // Gestión de Personas y Usuarios
    Route::get('/personas', [PersonaController::class, 'index'])->name('personas.index');
    Route::post('/personas/asignar-usuario', [PersonaController::class, 'storeUsuario'])->name('personas.asignar');
    Route::get('/personas/nuevo', [PersonaController::class, 'create'])->name('personas.create');
    Route::post('/personas/guardar', [PersonaController::class, 'store'])->name('personas.store');
    Route::post('/personas/{id}/toggle-estado', [PersonaController::class, 'toggleEstado'])->name('personas.toggle');

    // Servicios
    Route::resource("/servicio", ServicioController::class);
    Route::get("/servicio/{id}/usuarioservicio", [ServicioController::class, "funAsignar_usuario_servicio"])->name('servicio.asignar');
    Route::post("/servicio/{id}/usuarioservicio", [ServicioController::class, "funAsignar_usuario_servicioBD"])->name('servicio.asignar.post');

    // --- RUTAS DE TURNOS ---
    Route::get('/turnos/crear', [TurnoAsignadoController::class, 'create'])->name('turnos.create');
    Route::post('/turnos/guardar', [TurnoAsignadoController::class, 'store'])->name('turnos.store');
    Route::post('/turnos/store-rapido', [TurnoAsignadoController::class, 'store_rapido'])->name('turnos.store_rapido');
    Route::post('/turnos/clonar-semana-mes', [TurnoAsignadoController::class, 'clonarSemanaMes'])->name('turnos.clonarSemanaMes');
    Route::post('/turnos/clonar', [TurnoAsignadoController::class, 'clonar'])->name('turnos.clonar');
    Route::post('/turnos/rotar', [TurnoAsignadoController::class, 'rotar'])->name('turnos.rotar');
    Route::post('/turnos/eliminar-mes', [TurnoAsignadoController::class, 'eliminarMes'])->name('turnos.eliminarMes');
    Route::delete('/turnos/{id}', [TurnoAsignadoController::class, 'destroy'])->name('turnos.destroy');
    
    Route::resource('turnos', TurnoAsignadoController::class)->except(['index', 'show', 'create', 'store', 'destroy']);
});