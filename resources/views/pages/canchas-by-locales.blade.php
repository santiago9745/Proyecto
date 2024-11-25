@extends('layouts.app')

@section('content')
<div class="image-slider">
    <img src="/assets/img/futbol-11.jpg" alt="Imagen deportiva 1">
    <img src="/assets/img/young-people-playing-basketball.jpg" alt="Imagen deportiva 2">
</div>
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
        <p style="color: white">{{ session('error') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
        <p style="color: white">{{ session('success') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>               
@endif


    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.guest.navbar')
            </div>
        </div>
    </div>

    <main>
        <section>
            <div class="min-vh-45 mt-8">
                <div class="container">
                    <div class="row">
                        <div class="scroll-container" style="max-height: 610px; overflow-y: auto;">
                            <div class="row"> <!-- Asegúrate de tener una fila para los locales -->
                                <div class="card mb-5 pt-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalReservas">
                                        Ver Reservas Acumuladas
                                    </button>
                                </div>
                                <!-- Modal para mostrar las reservas acumuladas -->
                                <div class="modal fade" id="ModalReservas" tabindex="-1" aria-labelledby="ModalReservasLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="ModalReservasLabel">Reservas Acumuladas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Cancha</th>
                                                            <th>Fecha</th>
                                                            <th>Hora Inicio</th>
                                                            <th>Hora Fin</th>
                                                            <th>Precio por Media Hora (en Bs)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $total = 0; // Inicializa el total
                                                        @endphp
                                                        @foreach ($reservas as $reserva)
                                                            <tr>
                                                                <td>{{ $reserva->nombre}}</td>
                                                                <td>{{ $reserva->Fecha_Reserva}}</td>
                                                                <td>{{ $reserva->Hora_Inicio}}</td>
                                                                <td>{{ $reserva->Hora_Fin}}</td>
                                                                <td style="text-align: center;">{{ $reserva->precio}}</td>
                                                            </tr>
                                                            @php
                                                                $total += $reserva->precio;  
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="text-end mt-3 me-7">
                                                    <strong>Total: {{ $total }} Bs.</strong>
                                                </div>  
                                            </div>
                                            <div class="modal-footer">
                                                
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <form action="{{ route('moveReservationsToPermanent')}}" method="POST" id="formReservas" target="_blank">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">Guardar Reservas</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @foreach ($canchas as $cancha)
                                    <div class="col-md-6 col-sm-6">
                                        <div class="card mb-5">
                                            <div class="card-body">
                                                <div class="text-center mt-4">
                                                    <h5>{{ $cancha->nombre }}</h5>
                                                    <p><strong>Estado de la cancha:</strong> {{$cancha->estado_cancha}}</p>
                                                    <p><strong>Tipo de deporte:</strong> {{ $cancha->nombre_deporte }}</p>
                                                    <p><strong>Precio:</strong> {{ $cancha->precio }}</p>
                                                    <hr class="my-4">
                                                    <div class="mt-4">
                                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar{{ $cancha->ID_Cancha }}">
                                                            Reservar Cancha
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal para agregar reservas -->
                                    <div class="modal fade" id="ModalAgregar{{ $cancha->ID_Cancha }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $cancha->ID_Cancha }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel{{ $cancha->ID_Cancha }}">Agregar Reservas en {{ $cancha->nombre }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div style="max-height: 600px; overflow-y: auto;">
                                                        <div id="carousel{{ $cancha->ID_Cancha }}" class="carousel slide" data-bs-ride="carousel">
                                                            <div class="carousel-inner" style="min-height: 300px;">
                                                                @if (count($cancha->imagenes) > 0)
                                                                    @foreach ($cancha->imagenes as $index => $imagen)
                                                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                            <img src="{{ $imagen->URL }}" class="d-block w-100 img-fluid" alt="Imagen del local" style="object-fit: contain; max-height: 300px;">
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <div class="carousel-item active">
                                                                        <img src="{{ asset('img/imagen.jpg') }}" class="d-block w-100 img-fluid" alt="Imagen por defecto" style="object-fit: contain; max-height: 300px;">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $cancha->ID_Cancha }}" data-bs-slide="prev">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="visually-hidden">Previous</span>
                                                            </button>
                                                            <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $cancha->ID_Cancha }}" data-bs-slide="next">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="visually-hidden">Next</span>
                                                            </button>
                                                        </div>
                                                        <div class="container mt-4">
                                                            <div class="row">
                                                                @if (session('fecha_reserva'))
                                                                    <div class="col-md-6">
                                                                @else
                                                                    <div class="col-md-12">
                                                                @endif
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary text-white">Consulta de horarios</div>
                                                                        <div class="card-body">
                                                                            <form action="{{ route('getHorariosOcupados') }}" method="POST">
                                                                                @csrf
                                                                                <!-- Campo para la fecha -->
                                                                                <label for="fechaReserva" class="mr-2 ms-5">Seleccionar Fecha:</label>
                                                                                <div class="form-group d-flex justify-content-center">
                                                                                    
                                                                                    <input type="date" id="fechaReserva" name="fechaReserva" class="form-control w-50" value="{{ session('fecha_reserva') }}" required>
                                                                                    <input type="hidden" name="id" value="{{ $cancha->ID_Cancha }}">
                                                                                    <input type="hidden" name="idLocal" value="{{ $cancha->ID_Local }}">
                                                                                </div>
                                                                                <button type="submit" class="btn btn-primary">Consultar Horarios</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if (session('fecha_reserva'))
                                                                    <div class="col-md-6">
                                                                        <div class="card">
                                                                            <div class="card-header bg-primary text-white">Horarios disponibles</div>
                                                                            <div class="card-body">
                                                                                
                                                                                    <form action="{{ route('reservas-temporales.store') }}" method="POST" id="formReserva">
                                                                                        @csrf
                                                                                        <div class="mt-4">
                                                                                            <h6 class="text-center">Seleccionar Horarios</h6>
                                                                                            
                                                                                            <!-- Contenedor de los inputs de hora de inicio y fin -->
                                                                                            <div class="form-row d-flex justify-content-center mt-2">
                                                                                                <!-- Hora de inicio -->
                                                                                                <div class="form-group col-md-5">
                                                                                                    <label for="horaInicio" class="mr-2">Hora de Inicio:</label>
                                                                                                    <select id="horaInicio" name="hora_inicio" class="form-control" required>
                                                                                                        <option value="">Seleccione hora de inicio</option>
                                                                                                        @php
                                                                                                            $horasInicio = substr($cancha->Hora_Apertura, 0, 2); // Hora de inicio en formato 24h (por ejemplo, 8 AM)
                                                                                                            $horasFin = substr($cancha->Hora_Cierre, 0, 2);  // Hora de fin en formato 24h (por ejemplo, 8 PM)
                                                                                                            $intervalo = 30; // Intervalo en minutos
                                                                                                            $horarios = [];

                                                                                                            // Generar horarios en intervalos de 30 minutos
                                                                                                            for ($hora = $horasInicio; $hora < $horasFin; $hora++) {
                                                                                                                $horarios[] = sprintf("%02d:00", $hora);
                                                                                                                $horarios[] = sprintf("%02d:30", $hora);
                                                                                                            }
                                                                                                        @endphp

                                                                                                        @foreach ($horarios as $horario)
                                                                                                            @php
                                                                                                                // Verifica si el horario está reservado para la cancha actual y dentro del intervalo de una reserva existente
                                                                                                                $esReservado = isset($horariosReservados) && collect($horariosReservados)->contains(function($reservado) use ($horario, $cancha) {
                                                                                                                    $horaInicio = \Carbon\Carbon::parse($reservado->Hora_Inicio);
                                                                                                                    $horaFin = \Carbon\Carbon::parse($reservado->Hora_Fin);
                                                                                                                    $horaActual = \Carbon\Carbon::parse($horario);

                                                                                                                    return $reservado->ID_Cancha === $cancha->ID_Cancha && 
                                                                                                                        $horaActual->between($horaInicio, $horaFin);
                                                                                                                });
                                                                                                            @endphp

                                                                                                            @if (!$esReservado)
                                                                                                                <option value="{{ $horario }}">{{ $horario }}</option>
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>

                                                                                                <!-- Hora de fin -->
                                                                                                <div class="form-group col-md-5">
                                                                                                    <label for="horaFin" class="mr-2">Hora de Fin:</label>
                                                                                                    <select id="horaFin" name="hora_fin" class="form-control" required>
                                                                                                        <option value="">Seleccione hora de fin</option>
                                                                                                        <!-- Imprimir todas las horas, como en el combobox de inicio -->
                                                                                                        @foreach ($horarios as $horario)
                                                                                                            @php
                                                                                                                // Verifica si el horario está reservado para la cancha actual y dentro del intervalo de una reserva existente
                                                                                                                $esReservado = isset($horariosReservados) && collect($horariosReservados)->contains(function($reservado) use ($horario, $cancha) {
                                                                                                                    $horaInicio = \Carbon\Carbon::parse($reservado->Hora_Inicio);
                                                                                                                    $horaFin = \Carbon\Carbon::parse($reservado->Hora_Fin);
                                                                                                                    $horaActual = \Carbon\Carbon::parse($horario);

                                                                                                                    return $reservado->ID_Cancha === $cancha->ID_Cancha && 
                                                                                                                        $horaActual->between($horaInicio, $horaFin);
                                                                                                                });
                                                                                                            @endphp

                                                                                                            @if (!$esReservado)
                                                                                                                <option value="{{ $horario }}">{{ $horario }}</option>
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>

                                                                                            <!-- Campos ocultos para la reserva -->
                                                                                            <input type="hidden" name="cancha_id" value="{{ $cancha->ID_Cancha }}">
                                                                                            <input type="hidden" name="fecha" value="{{ session('fecha_reserva') }}">
                                                                                            <input type="hidden" name="precio" value="{{ $cancha->precio }}">
                                                                                            <input type="hidden" name="idUsuario" value="{{ auth()->user()->id }}">
                                                                                            <input type="hidden" name="idLocal" value="{{ $cancha->ID_Local }}">
                                                                                            <button type="submit" class="btn btn-primary mt-3">Reservar</button>
                                                                                        </div>
                                                                                    </form>
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                                               
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script>
        document.getElementById('formReservas').addEventListener('submit', function () {
        // Esperar un momento para permitir el envío antes de recargar
            setTimeout(function () {
                location.reload(); // Refrescar la página actual
            }, 500); // Medio segundo de espera (ajustable si es necesario)
        });
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('fechaReserva').min = new Date().toISOString().split("T")[0];
            @if(session('modal_open'))
                var idCancha = {{ session('id_cancha') }}; // Obtener el ID de la cancha de la sesión
                var modal = new bootstrap.Modal(document.getElementById('ModalAgregar' + idCancha), {
                    keyboard: false // Otras configuraciones si es necesario
                });
                modal.show(); // Mostrar el modal
    
                // Limpiar la variable de sesión para evitar que el modal se abra nuevamente en la próxima recarga
                @php
                    session()->forget('modal_open');
                    session()->forget('id_cancha');
                @endphp
            @endif
        });
        
    </script>
    <script>
        // Función para validar que la hora de fin no sea menor que la hora de inicio
        document.getElementById('horaInicio').addEventListener('change', function() {
            var horaInicio = this.value;
            var horaFinSelect = document.getElementById('horaFin');
            
            // Filtrar las opciones del combo de hora de fin para que no puedan ser menores que la hora de inicio
            for (var option of horaFinSelect.options) {
                var horaFin = option.value;
                if (horaFin <= horaInicio) {
                    option.disabled = true;  // Deshabilitar opción si la hora de fin es menor o igual a la hora de inicio
                } else {
                    option.disabled = false; // Habilitar opción si la hora de fin es mayor a la hora de inicio
                }
            }
        });
    </script>
    
    <style>
        .card-img-top {
            width: 100%; /* Ajusta el ancho al 100% del contenedor */
            height: 335px; /* Altura fija */
            object-fit: cover; /* Ajusta la imagen para llenar el contenedor sin distorsión */
        }
    </style>

@endsection
