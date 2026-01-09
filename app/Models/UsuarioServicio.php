<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioServicio extends Model
{
    protected $table = 'usuario_servicio';

    public $timestamps = false;

    protected $fillable = [
        'fecha_ingreso',
        'descripcion_usuario_servicio',
        'usuario_id',
        'servicio_id',
        'estado',
    ];
} // <-- La clase debe cerrar aquí