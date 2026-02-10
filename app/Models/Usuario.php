<?php

namespace App\Models;

// Usamos Authenticatable para permitir el inicio de sesión
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $table = 'usuario';

    // Desactivado porque tu tabla no tiene created_at/updated_at
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

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', 
        'estado' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Configuración de AdminLTE
    |--------------------------------------------------------------------------
    */
public function getMorphClass()
{
    return 'App\Models\Usuario';
}

    public function adminlte_profile_url()
    {
        return 'perfil'; 
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones de Base de Datos
    |--------------------------------------------------------------------------
    */

    /**
     * Relación con los Servicios asignados (Muchos a Muchos)
     */
    public function servicio() 
    {
        return $this->belongsToMany(Servicio::class, 'usuario_servicio', 'usuario_id', 'servicio_id');
    }

    /**
     * Relación con los Turnos asignados (Uno a Muchos)
     */
    public function turnosAsignados()
    {
        return $this->hasMany(TurnoAsignado::class, 'usuario_id', 'id');
    }

    /**
     * Relación con la tabla Personas (Uno a Uno)
     */
    public function persona()
    {
        return $this->hasOne(Persona::class, 'usuario_id', 'id');
    }
}