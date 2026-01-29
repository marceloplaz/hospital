<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    // 1. Nombre real de la tabla en tu DB
    protected $table = 'turno';

    // 2. CORRECCIÓN CRÍTICA: Tu llave primaria real es id_turno
    protected $primaryKey = 'id_turno';

    // 3. Desactivamos timestamps si tu tabla no tiene 'created_at' y 'updated_at'
    public $timestamps = false;

    // 4. Campos que permitimos guardar
    protected $fillable = [
        'nombre_turno',
        'duracion_horas',
        'hi',
        'hf'
    ];

    /**
     * Relación: Un turno aparece en muchas asignaciones.
     * El segundo parámetro es la FK en 'turno_asignado'.
     * El tercer parámetro es la PK en esta tabla ('id_turno').
     */
    public function asignaciones()
    {
        return $this->hasMany(TurnoAsignado::class, 'turno_id', 'id_turno');
    }

    /**
     * Si un turno pertenece a un usuario (creador).
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}