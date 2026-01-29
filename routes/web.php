<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TurnoAsignadoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\HomeController; // Importamos el HomeController

// 1. Redirección raíz: Si entra a la carpeta principal, al Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Rutas de Autenticación (Login, Logout, Registro)
Auth::routes();

// 3. RUTA DEL HOME (DASHBOARD) - CORREGIDA
// Esta es la ruta que cargará tu diseño con el Reloj y las Estadísticas
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// 4. Recurso de Personas
Route::resource('personas', PersonaController::class);

// 5. Rutas de Servicios
Route::resource("servicio", ServicioController::class);
Route::get("/servicio/{id}/usuarioservicio", [ServicioController::class, "funAsignar_usuario_servicio"])->name('servicio.asignar');
Route::post("/servicio/{id}/usuarioservicio", [ServicioController::class, "funAsignar_usuario_servicioBD"])->name('servicio.asignar.post');

// 6. Rutas de Asignación de Turnos
Route::get('/turnos/crear', [TurnoAsignadoController::class, 'create'])->name('turnos.create');
Route::post('/turnos/guardar', [TurnoAsignadoController::class, 'store'])->name('turnos.store');
Route::get('/turnos', [TurnoAsignadoController::class, 'index'])->name('turnos.index');

// 7. Reportes PDF
Route::get('personas/{id}/pdf', [PersonaController::class, 'generarPDF'])->name('personas.pdf');