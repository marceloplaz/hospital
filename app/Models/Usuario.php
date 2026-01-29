<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany; // Agregada para turnos
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'role_id',
        'servicio_id' 
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    /**
     * RELACIÓN FALTANTE: Esta es la que pide el Home
     * Un usuario tiene muchos turnos asignados en el cronograma semanal
     */
    public function turnosAsignados(): HasMany
    {
        // 'usuario_id' es la columna en la tabla 'turno_asignado'
        return $this->hasMany(TurnoAsignado::class, 'usuario_id');
    }

    public function persona(): HasOne
    {
        return $this->hasOne(Persona::class, 'usuario_id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function servicios(): BelongsToMany
    {
        return $this->belongsToMany(Servicio::class, 'usuario_servicio', 'usuario_id', 'servicio_id')
                    ->withPivot('descripcion_usuario_servicio', 'fecha_ingreso', 'estado');
    }
}