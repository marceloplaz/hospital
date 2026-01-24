<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    // 1. Forzar el nombre de la tabla en singular
    protected $table = 'gestion';

    // 2. Desactivar los timestamps (created_at y updated_at) 
    // porque tu tabla no los tiene
    public $timestamps = false;

    protected $fillable = ['anio'];

    public function meses()
    {
        return $this->hasMany(Mes::class, 'gestion_id');
    }
}
