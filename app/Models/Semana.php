<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semana extends Model
{
    // 1. Especificamos el nombre de la tabla en singular
    protected $table = 'semana';

    // 2. Desactivamos los timestamps (created_at y updated_at)
    public $timestamps = false;

    // 3. Definimos los campos que se pueden llenar masivamente
    protected $fillable = [
        'numero',
        'fecha_inicio',
        'fecha_fin',
        'mes_id'
    ];

    /**
     * Relación: Una semana pertenece a un Mes
     */
    public function mes()
    {
        return $this->belongsTo(Mes::class, 'mes_id');
    }

    /**
     * Relación: Una semana puede tener muchos turnos asignados
     */
    public function asignaciones()
    {
        return $this->hasMany(TurnoAsignado::class, 'semana_id');
    }
}