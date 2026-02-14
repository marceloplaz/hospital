<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurnoAsignado extends Model
{
    protected $table = 'turno_asignado';
    
    // Si no usas los campos created_at y updated_at en esta tabla, desactívalos:
    public $timestamps = false;

   protected $fillable = [
    'usuario_id', 
    'semana_id', 
    'servicio_id', 
    'dia', 
    'turno_id', // <--- Debe decir exactamente turno_id
    'estado',
    'observacion'
];

    // --- RELACIONES ---

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function semana()
    {
        return $this->belongsTo(Semana::class, 'semana_id');
    }

 // En TurnoAsignado.php
public function turnoDetalle()
{
    // Indica explícitamente la llave foránea y la local
    return $this->belongsTo(Turno::class, 'turno_id', 'id_turno');
}
}