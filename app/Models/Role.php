<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    // Si tu tabla en la DB se llama 'roles' (plural), Laravel lo detecta. 
    // Si se llama 'role' (singular), descomenta la siguiente línea:
    // protected $table = 'role';

    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Relación: Un rol pertenece a muchos usuarios
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'role_id');
    }

    /**
     * RELACIÓN FALTANTE: Un rol tiene muchos permisos
     * Esto permitirá que el método ->contains() funcione en la vista.
     */
    public function permisos(): BelongsToMany
    {
        // 'role_permiso' es el nombre típico de la tabla pivote
        return $this->belongsToMany(Permiso::class, 'role_permiso', 'role_id', 'permiso_id');
    }
}
