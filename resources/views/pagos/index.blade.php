<!DOCTYPE html

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> <head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <title>UTNPay</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<!-- CSS personalizado -->
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head> <body>
<div class="d-flex">
    @include('navbar')

    <div class="flex-grow-1 custom-padding p-4">
        <h1>PAGOS</h1>
        <div class="d-flex justify-content-between mb-3">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cargarPagoModal">Cargar Pago</button>
    <input type="text" id="searchInput" class="form-control w-50" placeholder="Buscar por Legajo o DNI">
</div>
        

        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Legajo</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody id="alumnosTableBody">
            @foreach ($alumnos as $alumno)
<tr>
    <td>{{ $alumno->id }}</td>
    <td>{{ $alumno->nombre }}</td>
    <td>{{ $alumno->apellido }}</td>
    <td>{{ $alumno->dni }}</td>
    <td>
    <button class="btn btn-primary btn-sm" onclick="verCursos({{ $alumno->id }})">Ver</button>
    </td>
</tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Modal para Cargar Pago -->
<div class="modal fade" id="cargarPagoModal" tabindex="-1" aria-labelledby="cargarPagoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cargarPagoModalLabel">Cargar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cargarPagoForm" action="/historial-pagos" method="POST">
    <div class="modal-body">
        <div class="mb-3">
            <label for="alumnoId" class="form-label">ID del Alumno</label>
            <input type="number" class="form-control" id="alumnoId" name="alumno_id" required>
        </div>
        <div class="mb-3">
            <label for="cursoId" class="form-label">ID del Curso</label>
            <input type="number" class="form-control" id="cursoId" name="curso_id" required>
        </div>
        <div class="mb-3">
            <label for="fechaPago" class="form-label">Fecha del Pago</label>
            <input type="date" class="form-control" id="fechaPago" name="fecha_pago" required>
        </div>
        <div class="mb-3">
            <label for="monto" class="form-label">Monto</label>
            <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar Pago</button>
    </div>
</form>
        </div>
    </div>
</div>
<!-- Modal para Historial de Pagos -->
<div class="modal fade" id="historialPagoModal" tabindex="-1" aria-labelledby="historialPagoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historialPagoModalLabel">Historial de Pagos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="historialPagoHeader">
                    <!-- Aquí se mostrará el encabezado del alumno y curso -->
                </div>
                <table class="table table-striped mt-4">
                    <thead>
                        <tr>
                            <th>Mes de Pago</th>
                            <th>Fecha de Pago</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody id="historialPagoTableBody">
                        <!-- Los pagos se cargarán aquí -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-primary" onclick="descargarPDF()">Descargar PDF</button>
</div>
        </div>
    </div>
</div>
<!-- Modal --> <div class="modal fade" id="cursosModal" tabindex="-1" aria-labelledby="cursosModalLabel" aria-hidden="true"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <h5 class="modal-title" id="cursosModalLabel">Cursos del Alumno</h5> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> </div> <div class="modal-body"> <table class="table table-striped"> <thead> <tr> <th>Curso</th> <th>Estado</th> </tr> </thead> <tbody id="cursosTableBody"> <!-- Los cursos se cargarán aquí --> </tbody> </table> </div> <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button> </div> </div> </div> </div>
 <!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    document.getElementById('cargarPagoForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/historial-pagos', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al guardar el pago');
        }
        return response.json();
    })
    .then(data => {
    
        // Limpiar el formulario
        document.getElementById('cargarPagoForm').reset();
    })
    .catch(error => {
        alert('Error: ' + error.message); // Mostrar mensaje de error
    });
});function verCursos(alumnoId) {
    // Hacer una solicitud AJAX para obtener los cursos del alumno
    $.get(`/alumnos/${alumnoId}/cursos`, function(cursos) {
        // Limpiar el cuerpo de la tabla
        $('#cursosTableBody').empty();

        // Agregar los cursos y el estado a la tabla
        cursos.forEach(function(curso) {
            let estadoClase = "";
            let estadoTexto = curso.pivot.estado;

            // Asignar clases según el estado
            switch (estadoTexto.toLowerCase()) {
                case "al dia":
                    estadoClase = "btn-success";
                    break;
                case "proximo a vencer":
                    estadoClase = "btn-warning";
                    break;
                case "vencido":
                    estadoClase = "btn-outline-danger";
                    break;
                case "baja":
                    estadoClase = "btn-danger";
                    break;
                default:
                    estadoClase = "btn-secondary"; // En caso de estado desconocido
            }

            $('#cursosTableBody').append(`
                <tr>
                    <td>${curso.nombre}</td>
                    <td>
                        <button class="btn btn-sm ${estadoClase}" onclick="verHistorialPago(${alumnoId}, ${curso.id})">
                            ${estadoTexto}
                        </button>
                    </td>
                </tr>
            `);
        });

        // Mostrar el modal de cursos
        $('#cursosModal').modal('show');
    }).fail(function() {
        alert('Error al obtener los cursos del alumno.');
    });
}
function verHistorialPago(alumnoId, cursoId) {
    $.get(`/alumnos/${alumnoId}/cursos/${cursoId}/historial-pagos`, function(data) {
        // Limpiar el contenido previo
        $('#historialPagoHeader').empty();
        $('#historialPagoTableBody').empty();

        // Configurar los datos del alumno y curso en el encabezado
        $('#historialPagoHeader')
            .html(`
                <h5>Alumno: ${data.alumno.nombre} ${data.alumno.apellido}</h5>
                <p>DNI: ${data.alumno.dni} | Legajo: ${data.alumno.id}</p>
                <h6>Curso: ${data.curso.nombre}</h6>
            `)
            .data('alumno-id', alumnoId) // Configurar el ID del alumno
            .data('curso-id', cursoId); // Configurar el ID del curso

        // Agregar los pagos a la tabla
        data.historial.forEach(function(item) {
            $('#historialPagoTableBody').append(`
                <tr>
                    <td>${item.mes}</td>
                    <td>${item.fecha_pago}</td>
                    <td>${item.monto}</td>
                </tr>
            `);
        });

        // Mostrar el modal de historial de pagos
        $('#historialPagoModal').modal('show');
    }).fail(function() {
        alert('Error al obtener el historial de pagos.');
    });
}
 document.getElementById('searchInput').addEventListener('input', function() {
    const filter = this.value.toLowerCase().trim(); // Convertir a minúsculas y eliminar espacios
    const rows = document.querySelectorAll('#alumnosTableBody tr');

    rows.forEach(row => {
        const legajo = row.cells[0].textContent.toLowerCase(); // Columna de Legajo
        const dni = row.cells[3].textContent.toLowerCase();   // Columna de DNI

        // Mostrar la fila si el filtro coincide con el inicio del legajo o del DNI
        if (legajo.startsWith(filter) || dni.startsWith(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
function descargarPDF() {
    const alumnoId = $('#historialPagoHeader').data('alumno-id');
    const cursoId = $('#historialPagoHeader').data('curso-id');

    if (alumnoId && cursoId) {
        // Abrir el PDF en una nueva pestaña o descargarlo
        window.open(`/alumnos/${alumnoId}/cursos/${cursoId}/historial-pagos/pdf`, '_blank');
    } else {
        alert('No se puede generar el PDF. Faltan datos del alumno o curso.');
    }
}
</script>



</body> </html> 