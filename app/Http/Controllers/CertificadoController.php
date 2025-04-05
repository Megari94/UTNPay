<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Curso;

class CertificadoController extends Controller
{
    public function index()
    {
        $cursos = Curso::all(); // Obtén todos los cursos
        // Mostrar el formulario para generar certificados
        return view('certificados.index', compact('cursos'));
    }

    public function visualizar(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string',
            'curso' => 'required|string',
            'modalidad' => 'required|string',
            'fecha' => 'required|date',
            'profesor' => 'required|string',
            'coordinadora' => 'required|string',
        ]);

        // Datos para el certificado
        $data = $request->all();

        // Generar el PDF en memoria
        $pdf = PDF::loadView('certificados.plantilla', $data)->setPaper('a4', 'landscape');
        // Mostrar el PDF en el navegador
        return $pdf->stream('certificado.pdf');
    }

    public function descargar(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string',
            'curso' => 'required|string',
            'modalidad' => 'required|string',
            'fecha' => 'required|date',
            'profesor' => 'required|string',
            'coordinadora' => 'required|string',
        ]);

        // Datos para el certificado
        $data = $request->all();

        // Generar el PDF para descargar
        $pdf = PDF::loadView('certificados.plantilla', $data);

        // Descargar el PDF
        return $pdf->download('certificado.pdf');
    }
    public function obtenerAlumnos(Request $request)
    {
        $cursoId = $request->curso_id;
        dd($cursoId); // Debug: Check if curso_id is received
    
        $alumnos = Alumno::whereHas('cursos', function ($query) use ($cursoId) {
            $query->where('curso_id', $cursoId)
                  ->where('estado', 'al día') // Ensure this matches your data
                  ->where('activo', true);
        })->get();
    
        dd($alumnos); // Debug: Check if any alumnos are retrieved
    
        return response()->json($alumnos);
    }
    public function enviarCertificados(Request $request)
    {
        $alumnos = Alumno::whereIn('id', $request->alumnos)->get();
        $curso = Curso::find($request->curso_id);
        $coordinador = $request->coordinador;
        $fecha = $request->fecha;

        foreach ($alumnos as $alumno) {
            // Generar el PDF del certificado
            $data = [
                'nombre' => $alumno->nombre,
                'curso' => $curso->nombre,
                'modalidad' => $curso->modalidad,
                'fecha' => $fecha,
                'profesor' => $curso->profesor,
                'coordinadora' => $coordinador,
            ];

            $pdf = PDF::loadView('certificados.plantilla', $data);

            // Enviar el correo
            Mail::to($alumno->correo)->send(new CertificadoMail($pdf));
        }

        return response()->json(['message' => 'Certificados enviados con éxito']);
    }
}