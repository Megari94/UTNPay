<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UTNPay - Certificados</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS personalizado -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Menú a la izquierda -->
        @include('navbar')

        <!-- Contenido principal a la derecha -->
        <div class="flex-grow-1 custom-padding p-4">
            <h1>Gestión de Certificados</h1>

            <!-- Formulario para generar un certificado -->
            <form action="{{ route('certificados.visualizar') }}" method="POST" target="_blank" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre del Alumno</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Juan Pérez" required>
                </div>
                <div class="col-md-4">
                    <label for="curso" class="form-label">Curso</label>
                    <input type="text" class="form-control" id="curso" name="curso" placeholder="Ej: Programación Web" required>
                </div>
                <div class="col-md-4">
                    <label for="modalidad" class="form-label">Modalidad</label>
                    <input type="text" class="form-control" id="modalidad" name="modalidad" placeholder="Ej: Virtual" required>
                </div>
                <div class="col-md-4">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
                <div class="col-md-4">
                    <label for="profesor" class="form-label">Profesor</label>
                    <input type="text" class="form-control" id="profesor" name="profesor" placeholder="Ej: Blas Pascal" required>
                </div>
                <div class="col-md-4">
                    <label for="coordinadora" class="form-label">Coordinador</label>
                    <input type="text" class="form-control" id="coordinadora" name="coordinadora" placeholder="Ej: María Antonieta" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-eye"></i> Visualizar PDF</button>
                </div>
            </form>

            <div class="col-md-4">
                <label for="curso_id" class="form-label">Curso</label>
                <select class="form-select" id="curso_id" name="curso_id" required>
                    <option value="" selected disabled>Seleccione un curso</option>
                    @foreach ($cursos as $curso)
                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-4">
                <button id="buscarAlumnos" class="btn btn-success">Buscar Alumnos</button>
                <button id="limpiarLista" class="btn btn-secondary">Limpiar Lista</button>
            </div>
            <div class="mt-4">
                <h2>Alumnos en condiciones de recibir certificados</h2>
                <table class="table table-striped mt-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>DNI</th>
                            <th>Correo</th>
                        </tr>
                    </thead>
                    <tbody id="alumnosTableBody">
                        <!-- Los alumnos se cargarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <button id="enviarCorreos" class="btn btn-primary" disabled>Enviar Correos</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Botón para buscar alumnos
        document.getElementById('buscarAlumnos').addEventListener('click', function () {
            const cursoId = document.getElementById('curso_id').value;
            console.log('Selected curso_id:', cursoId); // Debug: Verifica el curso_id seleccionado

            if (!cursoId) {
                alert('Por favor, seleccione un curso.');
                return;
            }

            fetch('{{ route("certificados.alumnos") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ curso_id: cursoId }),
            })
            .then(response => {
                console.log('Response status:', response.status); // Debug: Verifica el estado de la respuesta
                return response.json();
            })
            .then(alumnos => {
                console.log('Response from server:', alumnos); // Debug: Verifica la respuesta del servidor

                const tbody = document.getElementById('alumnosTableBody');
                tbody.innerHTML = ''; // Limpia la tabla

                if (alumnos.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5">No hay alumnos en condiciones de recibir certificados</td></tr>';
                } else {
                    alumnos.forEach((alumno, index) => {
                        const row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${alumno.nombre}</td>
                                <td>${alumno.apellido}</td>
                                <td>${alumno.dni}</td>
                                <td>${alumno.correo}</td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                }

                document.getElementById('enviarCorreos').disabled = alumnos.length === 0;
            })
            .catch(error => {
                console.error('Error fetching alumnos:', error); // Debug: Muestra errores en la consola
            });
        });

        // Botón para limpiar la lista
        document.getElementById('limpiarLista').addEventListener('click', function () {
            const tbody = document.getElementById('alumnosTableBody');
            tbody.innerHTML = ''; // Limpia la tabla
            document.getElementById('enviarCorreos').disabled = true; // Deshabilita el botón de enviar correos
        });

        // Botón para enviar correos
        document.getElementById('enviarCorreos').addEventListener('click', function () {
            const cursoId = document.getElementById('curso').value;
            const fecha = document.getElementById('fecha').value;
            const coordinador = document.getElementById('coordinadora').value;

            // Obtener los IDs de los alumnos
            const alumnos = Array.from(document.querySelectorAll('#alumnosTableBody tr')).map(row => row.cells[0].textContent);

            // Solicitud AJAX para enviar los certificados
            fetch('/certificados/enviar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ curso_id: cursoId, fecha, coordinador, alumnos }),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            });
        });
    </script>
</body>
</html>