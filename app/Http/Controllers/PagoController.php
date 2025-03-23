<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Alumno; // Importar el modelo Alumno

class PagoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::all(); // Asegúrate de que esta consulta devuelve datos
        return view('pagos.index', compact('alumnos'));
    }

    public function getCursos($id)
    {
        // Buscar el alumno por su ID
        $alumno = Alumno::findOrFail($id);

        // Obtener los cursos relacionados con el estado desde la tabla intermedia
        $cursos = $alumno->cursos()->withPivot('estado')->get();

        // Retornar los cursos y el estado como JSON
        return response()->json($cursos);
    }
    public function contarPagosPorAlumnoYCurso()
{
    // Obtener la cantidad de pagos realizados por alumno y curso
    $pagosPorAlumnoYCurso = DB::table('historial_pagos')
        ->select('alumno_id', 'curso_id', DB::raw('COUNT(*) as total_pagos'))
        ->groupBy('alumno_id', 'curso_id')
        ->get();

    // Actualizar la tabla alumnoxcurso con la cantidad de pagos realizados
    foreach ($pagosPorAlumnoYCurso as $pago) {
        DB::table('alumnoxcurso')
            ->where('alumno_id', $pago->alumno_id)
            ->where('curso_id', $pago->curso_id)
            ->update(['pagos_realizados' => $pago->total_pagos]);
    }

    return response()->json(['message' => 'Pagos realizados actualizados correctamente']);
}
}