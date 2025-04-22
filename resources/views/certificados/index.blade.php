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
        <form class="row g-3">
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
                <!-- <label for="profesor" class="form-label">Profesor</label> -->
                <!-- <input type="text" class="form-control" id="profesor" name="profesor" placeholder="Ej: Blas Pascal" required> -->
                <input type="hidden" name="profesor" value="Ing. Blas Pascal">

            </div>
            <div class="col-md-4">
                <!--label for="coordinadora" class="form-label">Director Académico</label-->
                <input type="hidden" name="coordinadora" value="Ing. Mariano López">

            </div>
            <div class="col-md-4">
                <label for="email" class="form-label">Email del Alumno</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Ej: alumno@example.com" required>
            </div>
            <div class="col-12">
                <!-- Botón para visualizar el PDF -->
                <button type="submit" formaction="{{ route('certificados.visualizar') }}" formmethod="POST" target="_blank" class="btn btn-primary">
                    <i class="bi bi-eye"></i> Visualizar PDF
                </button>
                <!-- Botón para enviar el certificado por correo -->
                <button type="button" id="enviarCertificado" class="btn btn-success">
                    <i class="bi bi-envelope"></i> Enviar por correo
                </button>
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
                            <th>Visualizar</th>
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
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
    <script>
        // Botón para enviar el certificado por correo
        document.getElementById('enviarCertificado').addEventListener('click', function () {
            const nombre = document.getElementById('nombre').value;
            const curso = document.getElementById('curso').value;
            const modalidad = document.getElementById('modalidad').value;
            const fecha = document.getElementById('fecha').value;
            const profesor = document.getElementById('profesor').value;
            const coordinadora = document.getElementById('coordinadora').value;
            const email = document.getElementById('email').value;

            if (!nombre || !curso || !modalidad || !fecha || !profesor || !coordinadora || !email) {
                alert('Por favor, complete todos los campos antes de enviar.');
                return;
            }

            fetch('{{ route("certificados.enviar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ nombre, curso, modalidad, fecha, profesor, coordinadora, email }),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                console.error('Error enviando el certificado:', error);
                alert('Ocurrió un error al enviar el certificado.');
            });
        });
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
            .then(data => {
                console.log('Response from server:', data); // Debug: Verifica la respuesta del servidor

                const tbody = document.getElementById('alumnosTableBody');
                tbody.innerHTML = ''; // Limpia la tabla

                // Verificar si la respuesta tiene la estructura esperada
                if (!data || (!Array.isArray(data.alumnos) && typeof data.alumnos !== 'object')) {
                    console.warn("No hay alumnos en condiciones:", data.message || "Formato inesperado");

                    tbody.innerHTML = `<tr><td colspan="5">${data.message || "No hay alumnos disponibles"}</td></tr>`;
                    document.getElementById('enviarCorreos').disabled = true;
                    return;
                }

                // Convertir un solo objeto en un array si es necesario
                const alumnos = Array.isArray(data.alumnos[0]) ? data.alumnos[0] : data.alumnos;



                // Si hay alumnos, cargarlos en la tabla
                alumnos.forEach((alumno, index) => {
                    console.log('Alumno:', alumno); // <--- Agregado para ver los datos exactos
                    const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${alumno.nombre}</td>
                            <td>${alumno.apellido}</td>
                            <td>${alumno.dni}</td>
                            <td>${alumno.correo}</td>
                            <td>
                                <a href="#" data-id="${alumno.id}" class="btn btn-primary btn-visualizar">
                                    <i class="bi bi-eye"></i> Visualizar
                                </a>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
                // Agrega un evento para los botones "Visualizar"
                document.querySelectorAll('.btn-visualizar').forEach(button => {
                button.addEventListener('click', function () {
                    const alumnoId = this.getAttribute('data-id');

                    // Crea el formulario dinámico
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ url("certificados/visualizar") }}/' + alumnoId;
                    form.target = '_blank';

                    // CSRF Token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrfInput);

                    // ID del alumno
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'alumno_id';
                    idInput.value = alumnoId;
                    form.appendChild(idInput);

                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);
                });
            });

                // Habilitar el botón de enviar correos
                document.getElementById('enviarCorreos').disabled = false;
            })



        // Botón para limpiar la lista
        document.getElementById('limpiarLista').addEventListener('click', function () {
            const tbody = document.getElementById('alumnosTableBody');
            tbody.innerHTML = ''; // Limpia la tabla
            document.getElementById('enviarCorreos').disabled = true; // Deshabilita el botón de enviar correos
        });
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