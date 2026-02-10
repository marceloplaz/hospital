<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';

    // Agregamos todos los campos que el controlador intentará guardar
    protected $fillable = [
        'usuario_id', 
        'nombres', 
        'apellidos', 
        'ci', 
        'telefono', 
        'direccion', 
        'tipo_trabajador', 
        'fecha_nacimiento', 
        'genero', 
        'nacionalidad',
        'salario',       
        'tipo_salario'   
    ];

    /**
     * Casting de atributos: 
     * Esto asegura que el salario siempre se trate como un número
     */
    protected $casts = [
        'salario' => 'decimal:2',
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Relación con el modelo Usuario
     * Si tu tabla de usuarios se llama 'usuario', Laravel lo encontrará correctamente
     */
    public function usuario()
    {
        // Especificamos 'usuario_id' como la llave foránea
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Atributo para obtener el nombre completo fácilmente
     * Útil para mostrar en listas o reportes: {{ $persona->nombre_completo }}
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }
}