<?php

namespace App\Models;

// Cambiamos Model por Authenticatable para que funcione el login
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';

    // Desactiva esto si tu tabla NO tiene las columnas created_at y updated_at
    public $timestamps = false; 

    protected $fillable = [
        'nombre', 
        'email', 
        'password', 
        'estado'
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

  // Dentro de la clase Usuario
public function adminlte_profile_url()
{
    return 'perfil'; // El nombre de la URL que pusimos en web.php
}
public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
    // Dentro de la clase Usuario
public function turnosAsignados()
{
    // Un usuario tiene muchos turnos asignados
    // 'usuario_id' es la columna en la tabla turno_asignado
    return $this->hasMany(TurnoAsignado::class, 'usuario_id', 'id');
}
public function persona()
{
    // Un usuario tiene una identidad en la tabla personas
    return $this->hasOne(Persona::class, 'usuario_id', 'id');
}
}