<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado</title>
    <style>
        body {
            font-family: Arial, sans-serif;            
            text-align: center;
            position: relative;         
            margin: -45;
            padding: -45;
            border: -45;
        }

        /* Imagen de fondo cubriendo toda la hoja */
        .fondo {
            position: fixed;            
            width: 115%;
            height:115%;
            background: url('{{ public_path('images/certificado_base.jpg') }}') no-repeat center center;
            background-size: cover;
        }

        /* Contenedor del contenido centrado */
        .contenido {
            position: relative;
            top: 40%;
            left: 65%;
            transform: translate(-50%, -50%);
            width: 60%;
            font-size: 25px;
            text-align: center;
        }

        /* Sección de firmas */
        .firma p {
            margin: 0;
            padding: 0;
        }

        /* Clase para separar el Profesor de la Coordinadora */
        .profesor-coordinadora {
            margin-right: 200px; /* Ajusta este valor para aumentar la distancia entre ellos */
        }

        .firma {
            position: relative;
            margin-top: 45%;
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 80%;
            margin-left: 35%;
            margin-right: auto;
            text-align: center;
            font-size: 16px;
            width: 60%;
        }

    </style>
</head>
<body>
    <div class="fondo"></div>

    <div class="contenido">
        <p>Se certifica que <strong>{{ $nombre }}</strong> ha completado el curso <strong>{{ $curso }}</strong> en la modalidad <strong>{{ $modalidad }}</strong>, 
        finalizado en la fecha <strong>{{ $fecha }}</strong>.</p>
    </div>
    
    <div class="firma">
        <!-- Aquí agregamos el espacio entre las firmas -->
        <p><strong>{{ $profesor }}</strong> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>{{ $coordinadora }}</strong></p>
        <p>Director &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Coordinador </p>
    </div>
</body>
</html>
