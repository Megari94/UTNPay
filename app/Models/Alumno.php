<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumnos'; // Nombre de la tabla en la base de datos
    public $timestamps = false;
    // Agregar los campos que se pueden asignar masivamente
    protected $fillable = [
        'dni',
        'apellido',
        'nombre',
        'telefono',
        'correo' 
    ];


    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'alumnoxcurso', 'alumno_id', 'curso_id')
                    ->withPivot('estado', 'pagos_realizados'); // Incluir los campos 'estado' y 'pagos_realizados' de la tabla intermedia
    }
    public function pagos()
    {
        return $this->hasMany(HistorialPago::class);
    }
}