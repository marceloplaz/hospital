<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas'; // Verifica si es 'persona' o 'personas' en tu DB

    protected $fillable = [
        'nombres', 'apellidos', 'usuario_id', 'tipo_trabajador', 
        'item', 'fecha_nacimiento', 'genero', 'telefono', 
        'direccion', 'nacionalidad', 'turno_id', 'servicio_id'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // RELACIÓN CON TURNO (Fundamental para el Reloj del Home)
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }
    public function persona(): HasOne
    {
    // Al definir la relación, Laravel usará el nombre de tabla definido en el modelo Persona
    return $this->hasOne(Persona::class, 'usuario_id');
    }

    // RELACIÓN CON SERVICIO (Fundamental para ver dónde trabaja)
 
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}