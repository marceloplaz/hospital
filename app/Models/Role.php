<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Permitir que estos campos se llenen mediante código
    protected $fillable = ['nombre', 'descripcion'];

    // Relación: Un rol lo tienen muchos usuarios
   public function usuarios() {
    return $this->belongsToMany(Usuario::class, 'role_usuario');
}
}