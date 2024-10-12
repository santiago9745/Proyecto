@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Recordatorios para reservas confirmadas'])

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
                        } elseif ($reserva->dias_restantes <= 7 && $reserva->dias_restantes >= 3) {
                            $color = 'yellow'; // Entre 3 y 7 días
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
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalNotificacion-{{ $reserva->ID_Reserva }}">
                                    Notificar sobre la reserva
                                </button>
                            </div>
                            <div class="card-footer text-center" style="background-color: #f8f9fa; border-radius: 0 0 15px 15px;">
                                <span class="badge badge-warning" style="color: black">{{ $reserva->Estado_Reserva }}</span>
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
                                        <div class="mb-3">
                                            <input type="hidden" name="email" value="{{$reserva->email}}">
                                            <input type="hidden" name="id" value="{{$reserva->ID_Reserva}}">
                                            <label class="form-label">Selecciona el tipo de mensaje</label>
                                            <div>
                                                <input type="radio" name="tipo_mensaje_{{ $reserva->ID_Reserva }}" id="mensaje_autogenerado_{{ $reserva->ID_Reserva }}" value="autogenerado" onclick="toggleMensaje('{{ $reserva->ID_Reserva }}', false)" checked>
                                                <label for="mensaje_autogenerado_{{ $reserva->ID_Reserva }}">Mensaje Autogenerado</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="tipo_mensaje_{{ $reserva->ID_Reserva }}" id="mensaje_personalizado_{{ $reserva->ID_Reserva }}" value="personalizado" onclick="toggleMensaje('{{ $reserva->ID_Reserva }}', true)">
                                                <label for="mensaje_personalizado_{{ $reserva->ID_Reserva }}">Mensaje Personalizado</label>
                                            </div>
                                        </div>

                                        <!-- Campo de mensaje autogenerado -->
                                        <div id="mensaje_autogenerado_input_{{ $reserva->ID_Reserva }}" style="display: block;">
                                            <textarea class="form-control" name="mensaje_autogenerado" rows="3" readonly>
                                                Estimado/a {{ $reserva->nombreCompleto }}, su reserva en {{ $reserva->nombre_cancha }} el día {{ \Carbon\Carbon::parse($reserva->Fecha_Reserva)->format('d-m-Y') }} está próxima. ¡Le esperamos!
                                            </textarea>
                                        </div>
                                        
                                        <!-- Campo para mensaje personalizado -->
                                        <div id="mensaje_personalizado_input_{{ $reserva->ID_Reserva }}" style="display: none;">
                                            <textarea class="form-control" name="mensaje_personalizado" rows="3"></textarea>
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

<script>
    // Función para mostrar/ocultar el campo de mensaje personalizado o autogenerado
    function toggleMensaje(id, showPersonalizado) {
        const mensajeAutogeneradoInput = document.getElementById('mensaje_autogenerado_input_' + id);
        const mensajePersonalizadoInput = document.getElementById('mensaje_personalizado_input_' + id);

        if (showPersonalizado) {
            mensajeAutogeneradoInput.style.display = 'none';
            mensajePersonalizadoInput.style.display = 'block';
        } else {
            mensajeAutogeneradoInput.style.display = 'block';
            mensajePersonalizadoInput.style.display = 'none';
        }
    }
</script>
