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
    @include('navbar')

    <div class="flex-grow-1 custom-padding p-4">
      <h1>Gestión de Certificados</h1>

      {{-- Formulario para generar un certificado individual --}}
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

        {{-- Hidden inputs con id para JS --}}
        <input type="hidden" id="profesor"     name="profesor"     value="Ing. Blas Pascal">
        <input type="hidden" id="coordinadora" name="coordinadora" value="Ing. Mariano López">

        <div class="col-md-4">
          <label for="email" class="form-label">Email del Alumno</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Ej: alumno@example.com" required>
        </div>
        <div class="col-12">
          <button type="submit"
                  formaction="{{ route('certificados.visualizar') }}"
                  formmethod="POST"
                  target="_blank"
                  class="btn btn-primary">
            <i class="bi bi-eye"></i> Visualizar PDF
          </button>
          <button type="button" id="enviarCertificado" class="btn btn-success">
            <i class="bi bi-envelope"></i> Enviar por correo
          </button>
        </div>
      </form>

      <hr>

      {{-- Búsqueda y listado de alumnos --}}
      <div class="row g-3">
        <div class="col-md-4">
          <label for="curso_id" class="form-label">Curso</label>
          <select class="form-select" id="curso_id" name="curso_id" required>
            <option value="" disabled selected>Seleccione un curso</option>
            @foreach($cursos as $curso)
              <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-8 d-flex align-items-end">
          <button id="buscarAlumnos" class="btn btn-success me-2">Buscar Alumnos</button>
          <button id="limpiarLista" class="btn btn-secondary">Limpiar Lista</button>
        </div>
      </div>

      <div class="mt-4">
        <h2>Alumnos en condiciones de recibir certificados</h2>
        <table class="table table-striped mt-2">
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
            {{-- Se rellenará vía JS --}}
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        <button id="enviarCorreos" class="btn btn-primary" disabled>Enviar Correos Masivos</button>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Envío individual
    document.getElementById('enviarCertificado').addEventListener('click', () => {
      const nombre       = document.getElementById('nombre').value;
      const curso        = document.getElementById('curso').value;
      const modalidad    = document.getElementById('modalidad').value;
      const fecha        = document.getElementById('fecha').value;
      const profesor     = document.getElementById('profesor').value;
      const coordinadora = document.getElementById('coordinadora').value;
      const email        = document.getElementById('email').value;

      if (!nombre || !curso || !modalidad || !fecha || !profesor || !coordinadora || !email) {
        return alert('Por favor, complete todos los campos.');
      }

      fetch('{{ route("certificados.enviar") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ nombre, curso, modalidad, fecha, profesor, coordinadora, email })
      })
      .then(res => res.json())
      .then(data => alert(data.message))
      .catch(() => alert('Error al enviar el correo.'));
    });

    // Búsqueda de alumnos
    document.getElementById('buscarAlumnos').addEventListener('click', () => {
      const cursoId = document.getElementById('curso_id').value;
      if (!cursoId) return alert('Seleccione un curso.');

      fetch('{{ route("certificados.alumnos") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ curso_id: cursoId })
      })
      .then(res => res.json())
      .then(data => {
        const tbody = document.getElementById('alumnosTableBody');
        tbody.innerHTML = '';
        if (!data.alumnos || data.alumnos.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6">No hay alumnos disponibles</td></tr>';
          document.getElementById('enviarCorreos').disabled = true;
          return;
        }
        data.alumnos.forEach((alumno, i) => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${i + 1}</td>
            <td>${alumno.nombre}</td>
            <td>${alumno.apellido}</td>
            <td>${alumno.dni}</td>
            <td>${alumno.correo}</td>
            <td>
              <a href="#" data-id="${alumno.id}" class="btn btn-sm btn-primary btn-visualizar">
                <i class="bi bi-eye"></i>
              </a>
            </td>
          `;
          tbody.appendChild(row);
        });
        document.getElementById('enviarCorreos').disabled = false;

        // Añadir evento a cada “Visualizar” recién creado
        document.querySelectorAll('.btn-visualizar').forEach(btn => {
          btn.addEventListener('click', () => {
            const alumnoId = btn.dataset.id;
            window.open(`/certificados/visualizar/${alumnoId}`, '_blank');
          });
        });
      });
    });

    // Limpiar lista
    document.getElementById('limpiarLista').addEventListener('click', () => {
      document.getElementById('alumnosTableBody').innerHTML = '';
      document.getElementById('enviarCorreos').disabled = true;
    });

    // ————————————————
    // Envío MASIVO CORRECTO
    // ————————————————
    document.getElementById('enviarCorreos').addEventListener('click', () => {
        // 1) Leer el select correcto:
        const cursoId     = document.getElementById('curso_id').value;
        // 2) Leer la fecha si la necesitas:
        const fecha       = document.getElementById('fecha').value;
        // 3) Leer el coordinador del hidden:
        const coordinador = document.getElementById('coordinadora').value;

        // 4) Extraer los IDs desde los data-attributes de los botones “Visualizar”:
        const alumnos = Array.from(
            document.querySelectorAll('#alumnosTableBody .btn-visualizar')
        ).map(btn => btn.dataset.id);

        // 5) Hacer POST a la ruta masiva
        fetch('{{ route("certificados.enviarMultiple") }}', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ curso_id: cursoId, fecha, coordinador, alumnos })
        })
        .then(res => res.json())
        .then(data => alert(data.message))
        .catch(err => {
            console.error(err);
            alert('Error al enviar los certificados.');
        });
    });

  </script>
</body>
</html>
