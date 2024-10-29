@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Recordatorios para reservas confirmadas'])
    <style>
        .btn-custom {
            background-color: #FFD700; /* Color amarillo dorado */
            color: white; /* Texto blanco para contraste */
        }
    
        .btn-custom:hover {
            background-color: #FFC107; /* Un tono más oscuro al pasar el mouse */
        }
    </style>
    
    <div class="container mt-5">
        
        @if(empty($sql))
            <div class="alert alert-info text-center">
                No tienes reservas confirmadas.
            </div>
        @else
            <div class="row justify-content-center">
                @foreach($sql as $reserva)
                    @php
                        // Asignamos colores según la cantidad de días restantes
                        if ($reserva->dias_restantes < 3 && $reserva->dias_restantes >= 0) {
                            $color = 'red'; // Menos de 3 días
                        } else {
                            $color = 'green'; // Más de 7 días
                        }
                    @endphp

                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm" style="border-radius: 15px;">
                            <!-- Header de la tarjeta -->
                            <div class="card-header text-white" style="background-color: #28a745; border-radius: 15px 15px 0 0;">
                                <!-- Fondo verde para el nombre de la cancha -->
                                <h5 class="card-title text-center" 
                                    style="margin: 0; font-weight: bold; background-color: #28a745; color: #fff; padding: 10px; border-radius: 10px;">
                                    {{ $reserva->nombre_cancha }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Usuario:</strong> {{ $reserva->nombreCompleto }}</p>
                                <p><strong>Correo electrónico:</strong> {{ $reserva->email }}</p>
                                <p><strong>Fecha de reserva:</strong> {{ \Carbon\Carbon::parse($reserva->Fecha_Reserva)->format('d-m-Y') }}</p>
                                <p><strong>Hora:</strong> {{ $reserva->Hora_Inicio }} - {{ $reserva->Hora_Fin }}</p>
                                <p><strong>Local:</strong> {{ $reserva->nombre_local }} ({{ $reserva->direccion_local }})</p>

                                <!-- Mostramos el contador dinámico -->
                                <p><strong>Tiempo restante:</strong>
                                    @if($reserva->dias_restantes >= 0)
                                        <span style="color: {{ $color }};">
                                            Quedan {{ $reserva->dias_restantes }} días y {{ $reserva->horas_restantes }} horas
                                        </span>
                                    @else
                                        <span style="color: grey;">La reserva ya ha pasado.</span>
                                    @endif
                                </p>

                                <!-- Botón que abre el modal -->
                                <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#ModalNotificacion-{{ $reserva->ID_Reserva }}">
                                    Enviar una notificacion personalizada
                                </button>
                            </div>
                            <div class="card-footer text-center" style="background-color: #f8f9fa; border-radius: 0 0 15px 15px;">
                                @php
                                    $estadoReserva = '';
                                    if ($reserva->Estado_Reserva == 1) {
                                        $estadoReserva = 'Confirmada';
                                    } elseif ($reserva->Estado_Reserva == 2) {
                                        $estadoReserva = 'Pendiente';
                                    } elseif ($reserva->Estado_Reserva == 0) {
                                        $estadoReserva = 'Cancelada'; // Si deseas manejar el estado 0
                                    }
                                @endphp
                                <span class="badge badge-warning" style="color: black">{{ $estadoReserva }}</span>
                            </div>
                            
                        </div>
                    </div>

                    <!-- Modal para notificar sobre la reserva -->
                    <div class="modal fade" id="ModalNotificacion-{{ $reserva->ID_Reserva }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Enviar notificación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('notificacion.enviar') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="email" value="{{$reserva->email}}">
                                        <input type="hidden" name="id" value="{{$reserva->ID_Reserva}}">

                                        <div class="mb-3">
                                            <label for="asunto" class="form-label">Asunto del mensaje</label>
                                            <input type="text" class="form-control" name="asunto" id="asunto" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Título del mensaje</label>
                                            <input type="text" class="form-control" name="titulo" required>
                                        </div>

                                        <!-- Campo para mensaje personalizado -->
                                        <div class="mb-3">
                                            <label class="form-label">Mensaje personalizado</label>
                                            <textarea class="form-control" name="mensaje" rows="3" required></textarea>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Enviar notificación</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>                    
                @endforeach
            </div>
        @endif
    </div>
@endsection
