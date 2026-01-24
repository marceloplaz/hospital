<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    // Definimos el nombre real de la tabla
    protected $table = 'turno';

    // Laravel asume que la PK es 'id'. Aquí la corregimos a 'id_turno'
    protected $primaryKey = 'id';
    public $timestamps = false;
    // Si tu PK no es un número autoincremental (aunque suele serlo), 
    // podrías necesitar: public $incrementing = true;

    protected $fillable = [
        'tipo',
        'duracion',
        'hi',
        'hf'
    ];

    /**
     * Relación: Un turno puede estar asignado muchas veces en la tabla pivote
     */
    public function asignaciones()
    {
        return $this->hasMany(TurnoAsignado::class, 'turno_id', 'id_turno');
    }

    /**
     * Relación: El usuario que posee este turno (dueño/creador)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
