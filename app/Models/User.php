<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Nombre de tu tabla personalizada
    protected $table = 'usuario';

    // Desactivamos timestamps porque tu tabla no tiene created_at/updated_at
    public $timestamps = false;

    // Campos que se pueden llenar
    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    // Ocultar la contraseña en consultas
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Encriptación automática de contraseña
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Dentro de tu clase User en app/Models/User.php

// Esto va dentro de la clase User
public function setNameAttribute($value)
{
    $this->attributes['nombre'] = $value;
}

public function getNameAttribute()
{
    return $this->nombre;
}



    // Relación con servicios
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'usuario_servicio', 'usuario_id', 'servicio_id')
                    ->withPivot('descripcion_usuario_servicio', 'estado', 'fecha_ingreso');
    }
}
