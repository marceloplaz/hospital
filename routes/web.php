<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\HomeController; // <--- Importante importar el controlador aquí
use App\Http\Controllers\TurnoAsignadoController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de Autenticación
Auth::routes();

// Rutas del Dashboard (Home)
Route::get('/home', [HomeController::class, 'index'])->name('home');


// Rutas de Servicios
// GET: Para ver el formulario de asignación
Route::get("/servicio/{id}/usuarioservicio", [ServicioController::class, "funAsignar_usuario_servicio"])->name('servicio.asignar');

// POST: Para procesar el formulario y guardar en la base de datos
Route::post("/servicio/{id}/usuarioservicio", [ServicioController::class, "funAsignar_usuario_servicioBD"])->name('servicio.asignar.post');

Route::resource("/servicio", ServicioController::class);

// Rutas de Asignación de Turnos
Route::get('/turnos/crear', [TurnoAsignadoController::class, 'create'])->name('turnos.create');
Route::post('/turnos/guardar', [TurnoAsignadoController::class, 'store'])->name('turnos.store');
Route::get('/turnos', [TurnoAsignadoController::class, 'index'])->name('turnos.index');