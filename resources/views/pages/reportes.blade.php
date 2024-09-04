@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Reportes'])

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h4 class="mb-0">Generar Reportes de canchas con mas demanda</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">Haz clic en el botón para generar el reporte de canchas con mayor demanda.</p>
                        <a href="{{ route('reportesCanchas') }}" class="btn btn-lg btn-outline-success mt-3">
                            <i class="fas fa-chart-bar me-2"></i> Generar Reporte
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h4 class="mb-0">Generar Reportes de Utilización de Canchas</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">Haz clic en el botón para generar el reporte de Utilización de Canchas</p>
                        <form method="GET" action="{{ route('reporteUtilidadCanchas') }}">
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_fin">Fecha Fin</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Generar Reporte</button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

