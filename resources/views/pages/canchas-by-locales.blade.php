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
                                                        <form action="{{ route('getHorariosOcupados') }}" method="POST">
                                                            @csrf
                                                            <!-- Campo para la fecha -->
                                                            <label for="fechaReserva" class="mr-2 ms-5">Seleccionar Fecha:</label>
                                                            <div class="form-group d-flex justify-content-center">
                                                                <input type="date" id="fechaReserva" name="fechaReserva" class="form-control w-50" required>
                                                                <input type="hidden" name="id" value="{{ $cancha->ID_Cancha }}">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Consultar Horarios</button>
                                                        </form>
                                                        <form>                    
                                                            <div class="mt-4">
                                                                <h6 class="text-center">Horarios Disponibles</h6>
                                                                <table class="table table-striped text-center mt-2" id="tablaHorarios">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Hora Inicio</th>
                                                                            <th>Hora Fin</th>
                                                                            <th>Reservar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                            $horasInicio = 8; // Hora de inicio en formato 24h (8 AM)
                                                                            $horasFin = 20; // Hora de fin en formato 24h (8 PM)
                                                                            $intervalo = 30; // Intervalo en minutos
                                                                            $horarios = [];
                                                                
                                                                            // Generar horarios en intervalos de 30 minutos
                                                                            for ($hora = $horasInicio; $hora < $horasFin; $hora++) {
                                                                                $horaInicio = sprintf("%02d:00", $hora);
                                                                                $horaFin = sprintf("%02d:30", $hora);
                                                                                $horarios[] = ['inicio' => $horaInicio, 'fin' => $horaFin];
                                                                
                                                                                $horaInicio = sprintf("%02d:30", $hora);
                                                                                $horaFin = sprintf("%02d:00", $hora + 1);
                                                                                $horarios[] = ['inicio' => $horaInicio, 'fin' => $horaFin];
                                                                            }
                                                                        @endphp
                                                                
                                                                        @foreach ($horarios as $horario)
                                                                            @php
                                                                                // Verifica si el horario está reservado para la cancha actual
                                                                                $esReservado = isset($horariosReservados) && collect($horariosReservados)->contains(function($reservado) use ($horario, $cancha) {
                                                                                    return $reservado->ID_Cancha === $cancha->ID_Cancha && 
                                                                                        \Carbon\Carbon::parse($reservado->Hora_Inicio)->format('H:i') === \Carbon\Carbon::parse($horario['inicio'])->format('H:i') &&
                                                                                        \Carbon\Carbon::parse($reservado->Hora_Fin)->format('H:i') === \Carbon\Carbon::parse($horario['fin'])->format('H:i');
                                                                                });
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $horario['inicio'] }}</td>
                                                                                <td>{{ $horario['fin'] }}</td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-primary" {{ $esReservado ? 'disabled' : '' }}>
                                                                                        Reservar
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>                                                                
                                                            </div>
                                                        </form>                                       
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div>
                                                        
                                                    </div>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="button" id="add-reserva" class="btn btn-primary">Agregar Reserva</button>
                                                    <button type="submit" id="saveReservas-" class="btn btn-success">Guardar</button>
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
        document.addEventListener("DOMContentLoaded", function() {
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
    
    <style>
        .card-img-top {
            width: 100%; /* Ajusta el ancho al 100% del contenedor */
            height: 335px; /* Altura fija */
            object-fit: cover; /* Ajusta la imagen para llenar el contenedor sin distorsión */
        }
    </style>

@endsection
