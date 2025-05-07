<?php
// filepath: d:\Marianela\UTNPay\app\Imports\AlumnosImport.php
namespace App\Imports;

use App\Models\Alumno;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class AlumnosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        //dd($row); // Esto te mostrará qué claves están llegando
        // Verificar si ya existe por DNI
        if (Alumno::where('dni', $row['dni'])->exists()) {
            return null; // Ignora este registro
        }

        return new Alumno([
            'nombre'   => $row['nombre'],
            'apellido' => $row['apellido'],
            'dni'      => $row['dni'],
            'telefono' => $row['telefono'],
            'correo'   => $row['correo'],
        ]);
    }
}