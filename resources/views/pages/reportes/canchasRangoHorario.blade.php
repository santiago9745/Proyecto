<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Utilización de Canchas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 10px;
            transition: background-color 0.3s;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #d1ecf1;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
            color: #666;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact; /* Para que se impriman los colores */
            }
        }
    </style>
</head>
<body>
    <h1>Reporte de Utilización de Canchas</h1>

    <table>
        <thead>
            <tr>
                <th>Cancha</th>
                <th>Rango Horario</th>
                <th>Total Reservas en Rango</th>
                <th>Porcentaje de Utilización</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportes as $reporte)
                <tr>
                    <td>{{ $reporte->cancha }}</td>
                    <td>{{ $reporte->rango_horario }}</td>
                    <td>{{ $reporte->total_reservas_rango }}</td>
                    <td>{{ $reporte->porcentaje_utilizacion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado el {{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
