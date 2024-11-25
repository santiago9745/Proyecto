<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }
    .report-title {
        text-align: center;
        font-size: 24px;
        color: #007bff;
        margin-bottom: 20px;
        font-weight: bold;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid black;
        table-layout: fixed; /* Asegura que las celdas se ajusten automáticamente */
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 6px 8px; /* Reduce el padding para ahorrar espacio */
        text-align: center;
        word-wrap: break-word; /* Asegura que el contenido largo se ajuste a la celda */
        font-size: 11px; /* Reduce el tamaño del texto en las celdas */
    }
    th {
        background-color: #007bff;
        color: white;
        font-size: 12px; /* Reduce el tamaño del texto en los encabezados */
    }
    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .badge {
        padding: 8px 12px;
        font-size: 12px;
        border-radius: 5px;
    }
    .text-right {
        text-align: right;
    }
    /* Ajuste de anchos de columnas específicas */
    th:nth-child(7), td:nth-child(7) {
        width: 15%; /* Ajusta el ancho de la columna Nombre Cliente */
    }
    th:nth-child(5), td:nth-child(5) {
        width: 10%; /* Ajusta el ancho de la columna Estado */
    }
</style>

<div class="container mt-5">
    <h2 class="report-title">Comprobante de Reservas</h2>

    @if(count($reservas) > 0)
        <!-- Display the 'nombre del cliente' from the first reservation -->
        <p style="text-align: left; font-size: 14px; margin: 5px 0;">
            <strong>Nombre del Cliente:</strong> {{ $reservas[0]->nombreCompleto }}
        </p>

        <!-- Display the 'nombre del local' from the first reservation -->
        <p style="text-align: left; font-size: 14px; margin: 5px 0;">
            <strong>Nombre del Local:</strong> {{ $reservas[0]->nombre_local }}
        </p>
    @endif

    <p style="text-align: left; font-size: 14px; margin: 5px 0;">
        Comprobante generado el: {{ now()->format('d/m/Y H:i') }}
    </p>
    
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Fecha de Reserva</th>
                <th>Hora de Inicio</th>
                <th>Hora de Fin</th>
                <th>Estado</th>
                <th>Total por Reserva (Bs)</th>
                <th>Descuento Aplicado (%)</th>
                <th>Total con Descuento (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGeneral = 0; // Inicializar el total general
            @endphp
            @foreach ($reservas as $reserva)
            @php
                    $totalGeneral += $reserva->total_por_reserva; // Sumar total_por_reserva
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($reserva->Fecha_Reserva)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->Hora_Inicio)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->Hora_Fin)->format('H:i') }}</td>
                    <td>{{ $reserva->Estado_Reserva }}</td>
                    <td>{{ number_format($reserva->precio) }} Bs</td>
                    <td>{{ $reserva->descuento }}%</td>
                    <td>{{ number_format($reserva->total_por_reserva,2)}} Bs</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">Total General:</th>
                <th class="text-right"> {{ number_format($totalGeneral, 2) }} Bs</th>
            </tr>
        </tfoot>
    </table>

    <p style="text-align: center; font-size: 14px; margin-top: 10px; color: #dc3545;">
        El monto total incluye los descuentos aplicados.
    </p>
</div>
