<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';

    protected $fillable = [
        'nombres', 
        'apellidos', 
        'usuario_id', 
        'tipo_trabajador', 
        'fecha_nacimiento', 
        'genero', 
        'telefono', 
        'direccion', 
        'nacionalidad'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
    public function role() {
    return $this->belongsTo(Role::class);
    }
}