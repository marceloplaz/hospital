<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';

    protected $fillable = [
        'nombre', 
        'email', 
        'password', 
        'estado', 
        'role_id' // Relación con roles
    ];
    // Relación Inversa: Un Usuario tiene una Persona
    public function persona()
    {
        return $this->hasOne(Persona::class, 'usuario_id');
    }

    // Relación con el Rol
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}

class Persona extends Model
{
    protected $table = 'personas';

    protected $fillable = [
        'nombres', 
        'apellidos', 
        'usuario_id', 
        'tipo_trabajador', 
        'item', // Campo ENUM: Item TGN, Item SUS, Contrato
        'fecha_nacimiento', 
        'genero', 
        'telefono', 
        'direccion', 
        'nacionalidad'
    ];

    /**
     * Relación directa: Una persona pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}