<style>
    body {
        font-family: Arial, sans-serif;
    }
    .report-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .report-date {
        text-align: right;
        margin-bottom: 20px;
        font-style: italic;
        color: #555;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #f9f9f9;
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th {
        background-color: #007bff;
        color: white;
        text-align: center;
        padding: 10px;
    }
    td {
        padding: 10px;
        text-align: center;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .total-row {
        font-weight: bold;
        background-color: #4caf50;
        color: white;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h4 class="mb-0">Listado de Canchas y Descuentos</h4>
                </div>
                <div class="card-body">
                    <h2 class="report-title">Reporte de Canchas y Descuentos</h2>
                    <p class="report-date">Generado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>

                    @if(count($canchasConDescuento) === 0)
                        <p class="lead text-center">No hay canchas registradas para este local.</p>
                    @else
                        <table>
                            <thead>
                                <tr>
                                    <th>Nombre Cancha</th>
                                        <th>Descuento (%)</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($canchasConDescuento as $cancha)
                                    <tr>
                                        <td>{{ $cancha->nombre_cancha }}</td>
                                        @if (is_null($cancha->descuento) || $cancha->descuento == 0)
                                            <td colspan="2" class="text-center">No tiene descuento</td>
                                        @else
                                            <td>{{ $cancha->descuento }}</td>
                                            <td>{{ $cancha->Fecha_Inicio }}</td>
                                            <td>{{ $cancha->Fecha_Fin }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="total-row">
                            <p>Total de canchas reportadas: {{ count($canchasConDescuento) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
