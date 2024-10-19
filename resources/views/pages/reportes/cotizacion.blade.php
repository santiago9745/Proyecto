<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
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
        border: 1px solid black; /* Añade un borde a la tabla */
    }
    table, th, td {
        border: 1px solid black; /* Añade un borde a las celdas */
    }
    th, td {
        padding: 12px 15px;
        text-align: center;
    }
    th {
        background-color: #007bff;
        color: white;
        font-size: 14px;
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
</style>

<div class="container mt-5">
    <h2 class="report-title">Cotización de Reservas</h2>

    <!-- Client and Local Info Section -->
    @if(count($reservas) > 0)
        <p style="text-align: left; font-size: 14px; margin: 5px 0;">
            <strong>Nombre del Cliente:</strong> {{ $reservas[0]->nombre_cliente }}
        </p>
        <p style="text-align: left; font-size: 14px; margin: 5px 0;">
            <strong>Nombre del Local:</strong> {{ $reservas[0]->nombre_local }}
        </p>
    @endif

    <p style="text-align: center; font-size: 14px; margin: 5px 0;">
        Cotización generada el: {{ now()->format('d/m/Y H:i') }}
    </p>
    
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Fecha de Reserva</th>
                <th>Hora de Inicio</th>
                <th>Hora de Fin</th>
                <th>Estado</th>
                <th>Total por Reserva (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGeneral = 0; // Inicializar el total
            @endphp
            @foreach ($reservas as $reserva)
                @php
                    $totalGeneral += $reserva->total_por_reserva; // Sumar el total por reserva
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($reserva->Fecha_Reserva)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->Hora_Inicio)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->Hora_Fin)->format('H:i') }}</td>
                    <td>{{ $reserva->Estado_Reserva }}</td>
                    <td>{{ number_format($reserva->total_por_reserva, 2) }} Bs</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total General:</th>
                <th class="text-right">{{ number_format($totalGeneral, 2) }} Bs</th> <!-- Alineación a la derecha -->
            </tr>
        </tfoot>
    </table>

    <p style="text-align: center; font-size: 14px; margin-top: 10px; color: #dc3545;">
        El monto total mostrado no incluye ningún descuento.
    </p>
</div>
