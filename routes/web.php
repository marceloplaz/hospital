<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Asegúrate de que esta línea esté presente
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TurnoAsignadoController;
use App\Http\Controllers\PersonaController;

// 1. Redirección raíz
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Rutas de Autenticación
Auth::routes();

// 3. Redirección inteligente de HOME
// Esto arregla el error 404 que veías después de loguearte
Route::get('/home', function () {
    return redirect()->route('personas.index');
})->name('home'); 

// 4. Recurso de Personas (Simplificado)
// El resource ya incluye: index, create, store, show, edit, update y destroy.
// No hace falta definirlas por separado abajo.
Route::resource('personas', PersonaController::class);

// 5. Rutas de Servicios
Route::resource("servicio", ServicioController::class);
Route::get("/servicio/{id}/usuarioservicio", [ServicioController::class, "funAsignar_usuario_servicio"])->name('servicio.asignar');
Route::post("/servicio/{id}/usuarioservicio", [ServicioController::class, "funAsignar_usuario_servicioBD"])->name('servicio.asignar.post');

// 6. Rutas de Asignación de Turnos
Route::get('/turnos/crear', [TurnoAsignadoController::class, 'create'])->name('turnos.create');
Route::post('/turnos/guardar', [TurnoAsignadoController::class, 'store'])->name('turnos.store');
Route::get('/turnos', [TurnoAsignadoController::class, 'index'])->name('turnos.index');
Route::get('personas/{id}/pdf', [App\Http\Controllers\PersonaController::class, 'generarPDF'])->name('personas.pdf');