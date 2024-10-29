<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #28a745;
            font-size: 24px;
            text-align: center;
        }

        p {
            font-size: 16px;
            color: #333;
            line-height: 1.5;
            margin-bottom: 20px;
            text-align: justify;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }

        .button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Título del correo -->
        <h1>{{ $titulo }}</h1>

        <!-- Mensaje principal -->
        <p>{{ $mensaje }}</p>


        <!-- Pie de página -->
        <div class="footer">
            <p>Este es un mensaje automático. Por favor, no respondas a este correo.</p>
            <p>&copy; {{ date('Y') }} Tu Empresa. Todos los derechos reservados.</p>
        </div>
    </div>

</body>
</html>
