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
    .state-pending {
        background-color: #ffeb3b;
        color: black;
    }
    .state-active {
        background-color: #4caf50;
        color: white;
    }
    .state-closed {
        background-color: #f44336;
        color: white;
    }
</style>

<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Reportes</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <h2 class="report-title">Reporte de Canchas con Mayor Demanda</h2>
                <p class="report-date">Generado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>

                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Deporte</th>
                            <th>Total de Reservas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cancha as $c)
                        <tr>
                            <td>{{ $c->nombre }}</td>
                            <td>{{ $c->nombre_deporte }}</td>
                            <td>{{ $c->total_reservas }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="total-row">
                    <p>Total de canchas reportadas: {{ count($cancha) }}</p>
                </div>
            </div>
        </div>        
    </div>
</div>
