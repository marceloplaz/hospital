<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    // Esto le dice a Laravel que use 'servicio' y no 'servicios'
    protected $table = 'servicio';

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuario_servicio', 'servicio_id', 'usuario_id')
                    ->withPivot('descripcion_usuario_servicio', 'estado', 'fecha_ingreso');
    }
}

