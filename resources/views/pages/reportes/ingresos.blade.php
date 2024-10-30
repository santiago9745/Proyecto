<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Reservas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
            overflow-x: hidden; /* Evitar el desbordamiento horizontal */
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
            font-size: 1.8em; /* Ajustar el tamaño del título */
        }

        .table-container {
            max-width: 100%; /* Limitar el ancho máximo */
            overflow-x: auto; /* Permitir desplazamiento horizontal si es necesario */
            margin: 20px 0;
        }

        table {
            width: auto; /* Cambiar a auto para ajustar el ancho de la tabla al contenido */
            max-width: 100%; /* Asegurar que no exceda el ancho de la página */
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            table-layout: auto; /* Ajustar el ancho de las columnas según el contenido */
        }

        th, td {
            padding: 8px 10px; /* Reducir el padding */
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 0.9em; /* Reducir el tamaño de la fuente */
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .total-ingresos {
            font-weight: bold;
            font-size: 1.2em;
            color: #28a745;
            text-align: right;
            margin: 20px;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .report-date {
            font-size: 0.9em;
            color: #555;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background-color: white;
            }

            table {
                max-width: 100%; /* Asegurar que la tabla ocupe el ancho completo en impresión */
            }
        }
    </style>
</head>
<body>

    <div class="report-header">
        <h1>Reporte de Reservas</h1>
        <div class="report-date">Fecha: {{ date('d/m/Y') }}</div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nombre del Usuario</th>
                    <th>Fecha Reserva</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Nombre Cancha</th>
                    <th>Nombre Local</th>
                    <th>Precio Base</th>
                    <th>Descuento (%)</th>
                    <th>Precio Final</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalIngresos = 0; // Inicializar el total de ingresos
                @endphp
                @foreach($reservas as $reserva)
                    <tr>
                        <td>{{ $reserva->Nombre_Usuario }}</td>
                        <td>{{ $reserva->Fecha_Reserva }}</td>
                        <td>{{ $reserva->Hora_Inicio }}</td>
                        <td>{{ $reserva->Hora_Fin }}</td>
                        <td>{{ $reserva->Nombre_Cancha }}</td>
                        <td>{{ $reserva->Nombre_Local }}</td>
                        <td>{{ number_format($reserva->Precio_Base, 2, ',', '.') }}</td>
                        <td>{{ number_format($reserva->Descuento, 2, ',', '.') }}</td>
                        <td>{{ number_format($reserva->Precio_Final, 2, ',', '.') }}</td>
                        @php
                            $totalIngresos += $reserva->Precio_Final; // Sumar el precio final a los ingresos totales
                        @endphp
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total-ingresos">Total Ingresos: {{ number_format($totalIngresos, 2, ',', '.') }}</div>  <!-- Mostrar total de ingresos -->

</body>
</html>
