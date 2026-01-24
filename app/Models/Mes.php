<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{
    // Nombre de la tabla en tu DB
    protected $table = 'mes';

    // Desactivamos timestamps si no los agregaste a esta tabla
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'gestion_id'
    ];

    /**
     * Relación: Un mes pertenece a una Gestión (Año)
     */
    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'gestion_id');
    }

    /**
     * Relación: Un mes tiene muchas semanas
     */
    public function semanas()
    {
        return $this->hasMany(Semana::class, 'mes_id');
    }
}
