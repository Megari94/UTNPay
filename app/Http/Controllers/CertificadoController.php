<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class CertificadoController extends Controller
{
    public function index()
    {
        // Mostrar el formulario para generar certificados
        return view('certificados.index');
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
}