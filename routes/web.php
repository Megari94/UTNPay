<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\HistorialPagosController;
use App\Http\Controllers\CursoController; 
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\AlumnoXCursoController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
Route::post('/alumnos/import', [AlumnoController::class, 'import'])->name('alumnos.import');

Route::get('/alumnos/{alumnoId}/cursos/{cursoId}/historial-pagos/pdf', [HistorialPagosController::class, 'generarPDF']);
Route::get('/alumnos/{alumnoId}/cursos/{cursoId}/historial-pagos', [HistorialPagosController::class, 'getHistorialPorCurso']);
Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');
Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');
Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
Route::get('/alumnos/{id}/cursos', [PagoController::class, 'getCursos'])->name('alumnos.cursos');
Route::post('/historial-pagos', [HistorialPagosController::class, 'store'])->name('historial_pagos.store');
Route::post('/alumnoxcurso', [AlumnoXCursoController::class, 'store'])->name('alumnoxcurso.store');

Route::get('/alumnos/{id}/cursos', [AlumnoXCursoController::class, 'getCursos'])->name('alumnos.cursos');


Route::post('/enviar-correo', [CorreoController::class, 'enviarATodos']);