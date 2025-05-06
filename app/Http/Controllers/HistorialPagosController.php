<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistorialPago;
use App\Models\Alumno;
use App\Models\Curso;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class HistorialPagosController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'curso_id' => 'required|exists:cursos,id',
            'fecha_pago' => 'required|date',
            'monto' => 'required|numeric|min:0',
        ]);

        // Guardar el pago en la base de datos
        HistorialPago::create($validated);

        return response()->json(['message' => 'Pago registrado exitosamente']);
    }
   
    
public function getHistorialPorCurso($alumnoId, $cursoId)
{
    $alumno = Alumno::findOrFail($alumnoId);
    $curso = Curso::findOrFail($cursoId);

    // Obtener los pagos realizados por el alumno para este curso
    $pagos = $alumno->pagos()->where('curso_id', $cursoId)->get();

    // Configurar el idioma en español
    \Carbon\Carbon::setLocale('es');

    // Generar las filas para cada mes del curso
    $mesInicio = $curso->mes_inicio; // Mes en el que comienza el curso
    $cantMeses = $curso->cant_meses; // Duración del curso en meses
    $historial = [];

    for ($i = 0; $i < $cantMeses; $i++) {
        $mesActual = ($mesInicio + $i - 1) % 12 + 1; // Calcular el mes actual (1-12)
        $anioActual = date('Y') + floor(($mesInicio + $i - 1) / 12); // Ajustar el año si se cruza al siguiente

        $fechaMes = sprintf('%04d-%02d', $anioActual, $mesActual); // Formato YYYY-MM
        $pago = $pagos->first(function ($pago) use ($fechaMes) {
            return strpos($pago->fecha_pago, $fechaMes) === 0; // Verificar si el pago pertenece al mes actual
        });

        // Obtener el nombre del mes en español
        $mes = \Carbon\Carbon::createFromDate($anioActual, $mesActual, 1)->translatedFormat('F Y');

        $historial[] = [
            'mes' => ucfirst($mes), // Capitalizar la primera letra del mes
            'fecha_pago' => $pago ? $pago->fecha_pago : 'ADEUDA', // Mostrar fecha de pago o "ADEUDA"
            'monto' => $pago ? $pago->monto : 'ADEUDA', // Mostrar monto o "ADEUDA"
        ];
    }

    return response()->json([
        'alumno' => $alumno,
        'curso' => $curso,
        'historial' => $historial,
    ]);
}
    
public function generarPDF($alumnoId, $cursoId)
{
    $alumno = Alumno::findOrFail($alumnoId);
    $curso = Curso::findOrFail($cursoId);

    // Obtener los pagos realizados por el alumno para este curso
    $pagos = $alumno->pagos()->where('curso_id', $cursoId)->get();

    // Configurar el idioma en español
    Carbon::setLocale('es');

    // Generar las filas para cada mes del curso
    $mesInicio = $curso->mes_inicio; // Mes en el que comienza el curso
    $cantMeses = $curso->cant_meses; // Duración del curso en meses
    $historial = [];

    for ($i = 0; $i < $cantMeses; $i++) {
        $mesActual = ($mesInicio + $i - 1) % 12 + 1; // Calcular el mes actual (1-12)
        $anioActual = date('Y') + floor(($mesInicio + $i - 1) / 12); // Ajustar el año si se cruza al siguiente

        $fechaMes = sprintf('%04d-%02d', $anioActual, $mesActual); // Formato YYYY-MM
        $pago = $pagos->first(function ($pago) use ($fechaMes) {
            return strpos($pago->fecha_pago, $fechaMes) === 0; // Verificar si el pago pertenece al mes actual
        });

        // Obtener el nombre del mes en español
        $mes = Carbon::createFromDate($anioActual, $mesActual, 1)->translatedFormat('F Y');

        $historial[] = [
            'mes' => ucfirst($mes), // Capitalizar la primera letra del mes
            'fecha_pago' => $pago ? $pago->fecha_pago : 'ADEUDA', // Mostrar fecha de pago o "ADEUDA"
            'monto' => $pago ? $pago->monto : 'ADEUDA', // Mostrar monto o "ADEUDA"
        ];
    }

    // Generar el PDF usando una vista
    $pdf = Pdf::loadView('pdf.historial', compact('alumno', 'curso', 'historial'));

    // Descargar el PDF
    return $pdf->download('historial-pagos.pdf');
}
}
