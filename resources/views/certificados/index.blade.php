<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UTNPay - Certificados</title>

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
                    <label for="coordinadora" class="form-label">Coordinadora</label>
                    <input type="text" class="form-control" id="coordinadora" name="coordinadora" placeholder="Ej: María Antonieta" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-eye"></i> Visualizar PDF</button>
                </form>
                <form action="{{ route('certificados.descargar') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="nombre" id="hidden-nombre">
                    <input type="hidden" name="curso" id="hidden-curso">
                    <input type="hidden" name="modalidad" id="hidden-modalidad">
                    <input type="hidden" name="fecha" id="hidden-fecha">
                    <input type="hidden" name="profesor" id="hidden-profesor">
                    <input type="hidden" name="coordinadora" id="hidden-coordinadora">
                    <button type="submit" class="btn btn-success"><i class="bi bi-download"></i> Descargar PDF</button>
                </form>
                </div>
            </form>

            
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>