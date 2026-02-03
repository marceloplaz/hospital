<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';

    protected $fillable = [
        'usuario_id', 
        'nombres', 
        'apellidos', 
        'ci', 
        'telefono', 
        'direccion', 
        'tipo_trabajador', 
        'fecha_nacimiento', 
        'genero', 
        'nacionalidad',
        'salario',       
        'tipo_salario'   
    ];

    /**
     * Relación con el modelo Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}