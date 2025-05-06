<?php

namespace App\Http\Controllers;

use App\Models\Alumno;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class AlertaController extends Controller
{
    public function index()
{
    // Obtener los alumnos con estado "proximo a vencer" o "vencido"
    $alumnos = Alumno::whereHas('cursos', function ($query) {
        $query->whereIn('estado', ['proximo a vencer', 'vencido']);
    })->get();

    // Retornar la vista con los datos
    return view('alertas.index', compact('alumnos'));
}
}
