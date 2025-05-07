<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\AlumnoXCurso;
use Illuminate\Support\Facades\Log;


class AlumnoXCursoController extends Controller
{
    /**
     * Almacena una nueva inscripción de alumno a un curso.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|exists:alumnos,dni',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        // Buscar el alumno por su DNI
        $alumno = Alumno::where('dni', $request->dni)->first();

        // Crear la inscripción en la tabla intermedia
        AlumnoXCurso::create([
            'alumno_id' => $alumno->id,
            'curso_id' => $request->curso_id,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('cursos.index')->with('success', 'Alumno inscrito correctamente.');
    }
    
    /**
    * Devuelve los cursos asociados a un alumno específico.
    */
    public function getCursos($id)
    {
        // Buscar al alumno con sus cursos
        $alumno = Alumno::with('cursos')->findOrFail($id);

        // Retornar los cursos en formato JSON
        return response()->json($alumno->cursos);
    }
    /**
     * Muestra una lista de todas las inscripciones.
     */
    public function index()
    {
        // Obtener todas las inscripciones
        $alumnosPorCurso = AlumnoXCurso::all(); // Puedes personalizar la consulta si es necesario

        // Retornar la vista con los datos
        return view('alumnoxcurso.index', compact('alumnosPorCurso'));
    }

    
}