<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use Illuminate\Support\Facades\Log;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::all();
        return view('cursos.index', compact('cursos'));
    } public function alumnosCompletaronCurso()
    {
        // Obtener los cursos y los alumnos que completaron el curso
        $cursos = Curso::with(['alumnos' => function ($query) {
            $query->join('cursos', 'cursos.id', '=', 'alumnoxcurso.curso_id')
                  ->whereColumn('alumnoxcurso.pagos_realizados', 'cursos.cant_meses');
        }])->get();

        return view('cursos.completaron', compact('cursos'));
    }

}
