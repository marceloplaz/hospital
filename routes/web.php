<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\HomeController; // <--- Importante importar el controlador aquí

Route::get('/', function () {
    return view('welcome');
});

// Rutas de Autenticación
Auth::routes();

// Rutas del Dashboard (Home)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rutas de Servicios
Route::get("/servicio/{id}/usuarioservicio", [ServicioController::class, "funAsignar_usuario_servicio"]);
Route::post("/servicio/{id}/usuarioservicio", [ServicioController::class, "storeUsuarioServicio"]);
Route::resource("/servicio", ServicioController::class);