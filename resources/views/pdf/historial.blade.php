<!DOCTYPE html>
<html>
<head>
    <title>Historial de Pagos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Historial de Pagos</h1>
    <h3>Alumno: {{ $alumno->nombre }} {{ $alumno->apellido }}</h3>
    <p>DNI: {{ $alumno->dni }} | Legajo: {{ $alumno->id }}</p>
    <h4>Curso: {{ $curso->nombre }}</h4>

    <table>
        <thead>
            <tr>
                <th>Mes de Pago</th>
                <th>Fecha de Pago</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($historial as $item)
            <tr>
                <td>{{ $item['mes'] }}</td>
                <td>{{ $item['fecha_pago'] }}</td>
                <td>{{ $item['monto'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>