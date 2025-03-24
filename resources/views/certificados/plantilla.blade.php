<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            position: relative;
            width: 100%;
            height: 100vh;
        }

        /* Imagen de fondo cubriendo toda la hoja */
        .fondo {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ public_path('images/certificado_base.jpg') }}') no-repeat center center;
            background-size: 100% 100%;
            z-index: -1;
        }

        /* Contenedor del contenido centrado */
        .contenido {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            font-size: 20px;
            text-align: center;
        }

        /* Sección de firmas */
        .firmas {
            position: absolute;
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .firma {
            text-align: center;
            font-size: 16px;
            width: 40%;
        }

        .linea-firma {
            margin-top: 50px;
            border-top: 2px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div class="fondo"></div>

    <div class="contenido">
        <p>Se certifica que <strong>{{ $nombre }}</strong> ha completado el curso <strong>{{ $curso }}</strong> en la modalidad <strong>{{ $modalidad }}</strong>, 
        finalizado en la fecha <strong>{{ $fecha }}</strong>.</p>
    </div>

    <div class="firmas">
        <div class="firma">
            <div class="linea-firma"></div>
            <p><strong>{{ $profesor }}</strong></p>
            <p>Profesor</p>
        </div>
        <div class="firma">
            <div class="linea-firma"></div>
            <p><strong>{{ $coordinadora }}</strong></p>
            <p>Coordinadora</p>
        </div>
    </div>
</body>
</html>
