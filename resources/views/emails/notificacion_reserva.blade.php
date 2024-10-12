<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        h1 {
            color: #28a745;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
            color: #333;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            font-size: 16px;
            color: #fff;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recordatorio de su Reserva</h1>
        <p>{{ $mensaje }}</p>
        <p class="footer">Si tienes preguntas, no dudes en <a href="mailto:cossio.santiago.132@gmail.com">contactarnos</a>.</p>
        <a href="http://tusitio.com/reservas" class="btn">Ver más detalles de su reserva</a>
    </div>
</body>
</html>
