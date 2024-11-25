@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Reportes'])

    <div class="container mt-5">
        <div class="row justify-content-center">
            
            <div class="col-md-6">
                <div class="card shadow-lg h-100">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h4 class="mb-0">Generar Reportes de Utilización de Canchas</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">Haz clic en el botón para generar el reporte de Utilización de Canchas</p>
                        <form method="GET" action="{{ route('reporteUtilidadCanchas') }}" target="_blank" onsubmit="return validarFechas('fecha_inicio1', 'fecha_fin1')">
                            <div class="form-group d-flex justify-content-center mt-3"> <!-- Added mt-3 here -->
                                <div class="me-2">
                                    <label for="fecha_inicio">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio1" class="form-control" required>
                                </div>
                                <div>
                                    <label for="fecha_fin">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin1" class="form-control" required>
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
                <div class="card shadow-lg h-100 ">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h4 class="mb-0">Generar Reporte de Usuarios con Más Reservas</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">Selecciona un rango de fechas para generar el reporte de usuarios con más reservas.</p>
                        <form method="GET" action="{{ route('reporteUsuarios') }}" target="_blank" onsubmit="return validarFechas('fecha_inicio2', 'fecha_fin2')">
                            <div class="form-group d-flex justify-content-center mt-3"> <!-- Added mt-3 here -->
                                <div class="me-2">
                                    <label for="fecha_inicio">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio2" class="form-control" required>
                                </div>
                                <div>
                                    <label for="fecha_fin">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin2" class="form-control" required>
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
                        <div class="card shadow-lg h-100 ">
                            <div class="card-header bg-success text-white text-center py-4">
                                <h4 class="mb-0">Generar Reporte de Ingresos</h4>
                            </div>
                            <div class="card-body text-center">
                                <p class="lead">Haz clic en el botón para generar el reporte de Ingresos</p>
                                <form method="GET" action="{{ route('reporteIngresos') }}" target="_blank" onsubmit="return validarFechas('fecha_inicio3', 'fecha_fin3')">
                                    <div class="form-group d-flex justify-content-center mt-3"> <!-- Added mt-3 here -->
                                        <div class="me-2">
                                            <label for="fecha_inicio">Fecha Inicio</label>
                                            <input type="date" name="fecha_inicio" id="fecha_inicio3" class="form-control" required>
                                        </div>
                                        <div>
                                            <label for="fecha_fin">Fecha Fin</label>
                                            <input type="date" name="fecha_fin" id="fecha_fin3" class="form-control" required>
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
                                <p class="lead">Selecciona el rango de fechas y la cancha para generar el reporte</p>
                                <form action="{{ route('rangoHorario') }}" method="POST" target="_blank" onsubmit="return validarFechas('fecha_inicio4', 'fecha_fin4')">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                            <input type="date" id="fecha_inicio4" name="fecha_inicio" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                            <input type="date" id="fecha_fin4" name="fecha_fin" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cancha" class="form-label">Selecciona la Cancha</label>
                                        <select id="cancha" name="cancha" class="form-select" required>
                                            <option value="" selected disabled>Elige una cancha</option>
                                            <!-- Aquí debes agregar las opciones de canchas dinámicamente -->
                                            @foreach($canchas as $cancha)
                                                <option value="{{ $cancha->ID_Cancha }}">{{ $cancha->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-lg btn-outline-success mt-3">
                                        <i class="fas fa-chart-bar me-2"></i> Generar Reporte
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
        </div>
    </div>
    <script>
        function validarFechas(fechaInicioId, fechaFinId) {
            const fechaInicio = document.getElementById(fechaInicioId).value;
            const fechaFin = document.getElementById(fechaFinId).value;
            
            if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                alert("La fecha de inicio no puede ser posterior a la fecha de fin.");
                return false; // Evita el envío del formulario
            }
            return true; // Permite el envío del formulario
        }
    </script>
@endsection
