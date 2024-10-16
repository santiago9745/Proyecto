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
        border: 1px solid black;
    }
    table, th, td {
        border: 1px solid black;
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
</style>

<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="report-title">Reporte de Usuarios con Más Reservas</h6>
                <br>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre Usuario</th>
                            <th>Email</th>
                            <th>Total Reservas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $u)
                        <tr>
                            <td>{{ $u->nombre_usuario }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <span class="badge 
                                    @if($u->total_reservas >= 10) badge-high 
                                    @elseif($u->total_reservas >= 5) badge-medium 
                                    @else badge-low @endif">
                                    {{ $u->total_reservas }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>
