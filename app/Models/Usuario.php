<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    public $timestamps = false; 

    protected $fillable = [
        'nombre', 
        'email', 
        'password', 
        'estado',
        'role_id',    // Necesario para el registro de personal
        'servicio_id' // Agregado según tus otras relaciones
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    // Relación con Persona
    public function persona(): HasOne
    {
        return $this->hasOne(Persona::class, 'usuario_id');
    }

    // Relación con el Rol (Asegúrate de que el archivo sea Role.php)
   // En app/Models/Usuario.php

public function rol(): BelongsTo
{
    // Usar la ruta completa elimina cualquier duda de Laravel
    return $this->belongsTo(\App\Models\Role::class, 'role_id');
}
    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function turnosAsignados(): HasMany
    {
        return $this->hasMany(TurnoAsignado::class, 'usuario_id', 'id');
    }
}