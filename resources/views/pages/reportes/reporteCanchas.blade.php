

<style>
    body {
        font-family: Arial, sans-serif;
    }
    .report-title {
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    .total-row {
        font-weight: bold;
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

                    <table>
                        <thead>
                            <tr>
                                <th>ID Cancha</th>
                                <th>Nombre</th>
                                <th>Estado Cancha</th>
                                <th>Deporte</th>
                                <th>Total de Reservas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cancha as $c)
                            <tr>
                                <td>{{ $c->ID_Cancha }}</td>
                                <td>{{ $c->nombre }}</td>
                                <td>{{ $c->estado_cancha }}</td>
                                <td>{{ $c->nombre_deporte }}</td>
                                <td>{{ $c->total_reservas }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>        
        </div>
    </div>