<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';
    protected $fillable = ['nombre', 'descripcion', 'profesor', 'fecha_hora'];
    public $timestamps = false;
    // Relación con la tabla alumnoxcurso
    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumnoxcurso', 'curso_id', 'alumno_id')
                    ->withPivot('estado', 'pagos_realizados'); // Incluir los campos 'estado' y 'pagos_realizados' de la tabla intermedia
    }
}