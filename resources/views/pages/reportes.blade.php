@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Reportes'])

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg h-100">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h4 class="mb-0">Generar Reportes de canchas con más demanda</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">Haz clic en el botón para generar el reporte de canchas con mayor demanda.</p>
                        <a href="{{ route('reportesCanchas') }}" class="btn btn-lg btn-outline-success mt-3" target="_blank">
                            <i class="fas fa-chart-bar me-2"></i> Generar Reporte
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-lg h-100">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h4 class="mb-0">Generar Reportes de Utilización de Canchas</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">Haz clic en el botón para generar el reporte de Utilización de Canchas</p>
                        <form method="GET" action="{{ route('reporteUtilidadCanchas') }}" target="_blank">
                            <div class="form-group d-flex justify-content-center mt-3"> <!-- Added mt-3 here -->
                                <div class="me-2">
                                    <label for="fecha_inicio">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                                </div>
                                <div>
                                    <label for="fecha_fin">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Generar Reporte</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-lg h-100 mt-3">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h4 class="mb-0">Generar Reporte de Usuarios con Más Reservas</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">Selecciona un rango de fechas para generar el reporte de usuarios con más reservas.</p>
                        <form method="GET" action="{{ route('reporteUsuarios') }}" target="_blank">
                            <div class="form-group d-flex justify-content-center mt-3"> <!-- Added mt-3 here -->
                                <div class="me-2">
                                    <label for="fecha_inicio">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                                </div>
                                <div>
                                    <label for="fecha_fin">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-lg btn-outline-success mt-4">
                                <i class="fas fa-chart-bar me-2"></i> Generar Reporte
                            </button>
                        </form>
                    </div>
                </div>
            </div>
                    <div class="col-md-6">
                        <div class="card shadow-lg h-100 mt-3">
                            <div class="card-header bg-success text-white text-center py-4">
                                <h4 class="mb-0">Generar Reporte de Canchas y Descuentos</h4>
                            </div>
                            <div class="card-body text-center">
                                <p class="lead">Haz clic en el botón para generar el reporte de canchas con sus descuentos según el local.</p>
                                <a href="{{ route('reportePromocion') }}" class="btn btn-lg btn-outline-success mt-3" target="_blank">
                                    <i class="fas fa-chart-bar me-2"></i> Generar Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="card shadow-lg h-100 mt-3">
                            <div class="card-header bg-success text-white text-center py-4">
                                <h4 class="mb-0">Generar Reporte de Ingresos</h4>
                            </div>
                            <div class="card-body text-center">
                                <p class="lead">Haz clic en el botón para generar el reporte de Ingresos</p>
                                <form method="GET" action="{{ route('reporteIngresos') }}" target="_blank">
                                    <div class="form-group d-flex justify-content-center mt-3"> <!-- Added mt-3 here -->
                                        <div class="me-2">
                                            <label for="fecha_inicio">Fecha Inicio</label>
                                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                                        </div>
                                        <div>
                                            <label for="fecha_fin">Fecha Fin</label>
                                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-lg btn-outline-success mt-4">
                                        <i class="fas fa-chart-bar me-2"></i> Generar Reporte
                                    </button>
                                </form> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="card shadow-lg h-100 mt-3">
                            <div class="card-header bg-success text-white text-center py-4">
                                <h4 class="mb-0">Generar reporte de uso de instalaciones por franja horaria</h4>
                            </div>
                            <div class="card-body text-center">
                                <p class="lead">Haz clic en el botón para generar el reporte de uso de instalaciones por franja horaria</p>
                                <a href="{{ route('rangoHorario') }}" class="btn btn-lg btn-outline-success mt-3" target="_blank">
                                    <i class="fas fa-chart-bar me-2"></i> Generar Reporte
                                </a>
                            </div>
                        </div>
                    </div>
        </div>
    </div>
@endsection
