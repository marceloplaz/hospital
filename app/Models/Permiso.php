<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permiso extends Model
{
    // Si tu tabla se llama 'permisos', Laravel la encontrará automáticamente.
    // Si se llama 'permiso', descomenta la línea de abajo:
    // protected $table = 'permiso';

    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Relación inversa: Un permiso pertenece a muchos roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permiso', 'permiso_id', 'role_id');
    }
}