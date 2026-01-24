<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    // Esto le dice a Laravel que use 'servicio' y no 'servicios'
    protected $table = 'servicio';

    // app/Models/Servicio.php
public function usuarios()
{
    // Esta relación busca en 'usuario_servicio' los IDs de 'usuario' y 'servicio'
    return $this->belongsToMany(User::class, 'usuario_servicio', 'servicio_id', 'usuario_id')
                ->withPivot('descripcion_usuario_servicio', 'estado', 'fecha_ingreso')
                ->withTimestamps();
}
}

