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
    .badge-high {
        background-color: #28a745;
        color: white;
    }
    .badge-medium {
        background-color: #ffc107;
        color: white;
    }
    .badge-low {
        background-color: #dc3545;
        color: white;
    }
    .progress-bar {
        height: 8px;
        background-color: #007bff;
    }
    .progress-container {
        background-color: #e9ecef;
        border-radius: 20px;
    }
</style>

<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="report-title">Reporte de Canchas con Mayor Demanda</h6>
                <p style="text-align: left; font-size: 14px; margin: 5px 0;">
                    Reporte generado desde: {{ $fechaInicio }} Hasta: {{ $fechaFin }}
                </p><br>
                <div class="color-legend">
                    <strong>Leyenda de Colores:</strong>
                    <span class="badge badge-high">Alta Demanda</span>
                    <span class="badge badge-medium">Demanda Media</span>
                    <span class="badge badge-low">Baja Demanda</span>
                </div><br>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre Cancha</th>
                            <th>Número de Reservas</th>
                            <th>Horas de Utilización</th>
                            <th>Porcentaje de Ocupación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($utilizacionCanchas as $c)
                        <tr>
                            <td>{{ $c->nombre_cancha }}</td>
                            <td>
                                <span class="badge @if($c->numero_reservas >= 10) badge-high 
                                    @elseif($c->numero_reservas >= 5) badge-medium 
                                    @else badge-low @endif">
                                    {{ $c->numero_reservas }}
                                </span>
                            </td>
                            <td>{{ $c->horas_utilizacion }} hrs</td>
                            <td>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: {{ $c->porcentaje_ocupacion }}%;"></div>
                                </div>
                                <span>{{ number_format($c->porcentaje_ocupacion, 2) }}%</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>