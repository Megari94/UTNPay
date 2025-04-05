<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Models\Curso;
use App\Models\Alumno;
use App\Models\CertificadoMail;

class CertificadoController extends Controller
{
    public function index()
    {
        $cursos = Curso::all(); // Obtén todos los cursos
        // Mostrar el formulario para generar certificados
        return view('certificados.index', compact('cursos'));
    }
    public function enviarCertificado(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string',
            'curso' => 'required|string',
            'modalidad' => 'required|string',
            'fecha' => 'required|date',
            'profesor' => 'required|string',
            'coordinadora' => 'required|string',
            'email' => 'required|email',
        ]);

        // Datos para el certificado
        $data = $request->all();

        // Generar el PDF utilizando la misma vista que el botón de "Visualizar"
        $pdf = PDF::loadView('certificados.plantilla', $data)
            ->setPaper('a4', 'landscape');
            

        // Enviar el correo
        try {
            Mail::to($data['email'])->send(new CertificadoMail($pdf));
            return response()->json(['message' => 'Certificado enviado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al enviar el certificado: ' . $e->getMessage()], 500);
        }
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
    
        // Debug: Verifica si el curso_id se recibe correctamente
        if (!$cursoId) {
            return response()->json(['error' => 'Curso ID no recibido'], 400);
        }
    
        // Filtrar alumnos que tienen el estado "al día" y están activos
        $alumnos = Alumno::whereHas('cursos', function ($query) use ($cursoId) {
            $query->where('curso_id', $cursoId)
                  ->where('estado', 'al día') // Verifica que el estado sea "al día"
                  ->where('activo', true); // Verifica que el alumno esté activo en el curso
        })->get();
    
        // Debug: Verifica si se encontraron alumnos
        if ($alumnos->isEmpty()) {
            return response()->json(['message' => 'No hay alumnos en condiciones'], 200);
        }
    
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