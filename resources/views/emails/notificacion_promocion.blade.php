<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promoción Especial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f7f7f7;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        .promocion {
            background-color: #e0f7fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>¡Hola {{ $nombre_usuario }}!</h2>  <!-- Cambiado a $nombre_usuario -->
        
        <p>Tenemos una promoción especial para ti:</p>

        <div class="promocion">
            <p><strong>Descuento: </strong>{{ $promocion->descuento }}%</p>
            <p><strong>Válido desde: </strong>{{ \Carbon\Carbon::parse($promocion->Fecha_Inicio)->format('d/m/Y') }}</p>
            <p><strong>Hasta: </strong>{{ \Carbon\Carbon::parse($promocion->Fecha_Fin)->format('d/m/Y') }}</p>
        </div>

        <p>No te pierdas esta oportunidad y realiza tu reserva en nuestro local.</p>

        <div class="footer">
            <p>Gracias por confiar en nosotros.</p>
            <p>El equipo de {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
