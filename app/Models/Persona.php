<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';

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
        'item', // Campo ENUM: Item TGN, Item SUS, Contrato
        'fecha_nacimiento', 
        'genero', 
        'telefono', 
        'direccion', 
        'nacionalidad'
    ];

    // Relación directa: Una persona pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
}