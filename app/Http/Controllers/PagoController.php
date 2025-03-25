<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Curso;

class PagoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::all();
        return view('pagos.index', compact('alumnos'));
    }

    public function getCursos($id)
    {
        $alumno = Alumno::findOrFail($id);
        $cursos = $alumno->cursos()->withPivot('estado')->get();
        return response()->json($cursos);
    }

    public function contarPagosPorAlumnoYCurso()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $pagosPorAlumnoYCurso = DB::table('historial_pagos')
            ->select('alumno_id', 'curso_id', DB::raw('COUNT(*) as total_pagos'))
            ->groupBy('alumno_id', 'curso_id')
            ->get();

        foreach ($pagosPorAlumnoYCurso as $pago) {
            DB::table('alumnoxcurso')
                ->where('alumno_id', $pago->alumno_id)
                ->where('curso_id', $pago->curso_id)
                ->update(['pagos_realizados' => $pago->total_pagos]);
        }

        $alumnosSinPago = DB::table('alumnoxcurso')
            ->join('cursos', 'alumnoxcurso.curso_id', '=', 'cursos.id')
            ->select('alumnoxcurso.alumno_id', 'alumnoxcurso.curso_id', 'cursos.fecha_inicio')
            ->get();

        foreach ($alumnosSinPago as $alumno) {
            $fechaInicio = \Carbon\Carbon::parse($alumno->fecha_inicio);
            $mesesDesdeInicio = $fechaInicio->diffInMonths(now());

            $pagos = DB::table('historial_pagos')
                ->where('alumno_id', $alumno->alumno_id)
                ->where('curso_id', $alumno->curso_id)
                ->orderBy('fecha_pago', 'asc')
                ->pluck('fecha_pago');

            $mesesSinPago = 0;
            $ultimoMesPago = $fechaInicio->month;

            foreach ($pagos as $pago) {
                $mesPago = \Carbon\Carbon::parse($pago)->month;
                if ($mesPago > $ultimoMesPago + 1) {
                    $mesesSinPago++;
                }
                $ultimoMesPago = $mesPago;
            }

            if ($mesesSinPago >= 2 || ($mesesDesdeInicio >= 2 && $pagos->isEmpty())) {
                DB::table('alumnoxcurso')
                    ->where('alumno_id', $alumno->alumno_id)
                    ->where('curso_id', $alumno->curso_id)
                    ->update(['estado' => 'baja']);
            }
        }

        return response()->json(['message' => 'Pagos realizados actualizados correctamente']);
    }

    public function buscarAlumnoPorDni(Request $request)
    {
        $dni = $request->query('dni');
        $alumno = Alumno::where('dni', $dni)->first();
        return response()->json(['alumno' => $alumno]);
    }

    public function buscarCursoPorNombre(Request $request)
    {
        $nombre = $request->query('nombre');
        $curso = Curso::where('nombre', 'like', "%$nombre%")->first();
        return response()->json(['curso' => $curso]);
    }
}