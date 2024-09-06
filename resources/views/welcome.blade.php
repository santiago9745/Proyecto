@extends('layouts.app')

@section('content')
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
            <div class="page-header min-vh-45">
                @foreach ($locales as $local)
                    <div class="container">   
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card-body">
                                    <div class="square"> 
                                        <h6 class="tituloLocales">{{ $local->nombre }}</h6>
                                        <p class="parrafoLocales">{{ $local->direccion }}</p>
                                        <div class="ps-4">
                                            <!-- Modal con ID único para cada local -->
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar{{ $local->ID_Local }}">
                                                Agregar cancha
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para agregar canchas al local actual -->
                    <div class="modal fade" id="ModalAgregar{{ $local->ID_Local }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $local->ID_Local }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel{{ $local->ID_Local }}">Agregar Canchas en {{ $local->nombre }}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('reserva') }}" method="POST">
                                        @csrf
                                        <div id="reserva-container-{{ $local->ID_Local }}">
                                            <!-- Campo inicial de reserva -->
                                            <div class="reserva-group mb-3">
                                                <label class="form-label">Canchas disponibles</label>
                                                <select class="form-select" name="reservas[0][canchas]" required>
                                                    <option value="...">...</option>  
                                                    @foreach ($local->canchas as $cancha)
                                                        <option value="{{ $cancha->ID_Cancha }}">{{ $cancha->nombre }}</option>    
                                                    @endforeach
                                                </select>
                                                <!-- Campos adicionales para la fecha y hora -->
                                                <label class="form-label">Fecha de la reserva</label>
                                                <input type="date" class="form-control" name="reservas[0][fecha]" required>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Hora inicio</label>
                                                        <select class="form-select" name="reservas[0][horaInicio]" required>
                                                            @for ($i = 8; $i <= 20; $i += 0.5)
                                                                @php
                                                                    $hora = intval($i);
                                                                    $minutos = ($i - $hora) * 60;
                                                                    $horaFormateada = sprintf("%02d:%02d", $hora, $minutos);
                                                                @endphp
                                                                <option value="{{ $horaFormateada }}">{{ $horaFormateada }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Hora fin</label>
                                                        <select class="form-select" name="reservas[0][horaFin]" required>
                                                            @for ($i = 8; $i <= 20; $i += 0.5)
                                                                @php
                                                                    $hora = intval($i);
                                                                    $minutos = ($i - $hora) * 60;
                                                                    $horaFormateada = sprintf("%02d:%02d", $hora, $minutos);
                                                                @endphp
                                                                <option value="{{ $horaFormateada }}">{{ $horaFormateada }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-danger mt-2 remove-cancha">Eliminar</button>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <!-- Botón para hacer otra reserva -->
                                            <button type="button" class="btn btn-primary" id="add-reserva-{{ $local->ID_Local }}">Hacer otra reserva</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Agregar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <button type="button" class="btn btn-primary" id="add-reserva">hacer otra reserva</button>
            </div>    
        </section>
    </main>
    <script>
        @foreach ($locales as $local)
            let counter{{ $local->ID_Local }} = 1; // Contador para las reservas del local actual
    
            document.getElementById('add-reserva-{{ $local->ID_Local }}').addEventListener('click', function() {
                counter{{ $local->ID_Local }}++;
                const container = document.getElementById('reserva-container-{{ $local->ID_Local }}');
                const newGroup = document.createElement('div');
                newGroup.className = 'reserva-group mb-3';
                newGroup.innerHTML = `
                    <label class="form-label">Canchas disponibles</label>
                    <select class="form-select" name="reservas[${counter{{ $local->ID_Local }} }][canchas]" required>
                        <option value="...">...</option>  
                        @foreach ($local->canchas as $cancha)
                            <option value="{{ $cancha->ID_Cancha }}">{{ $cancha->nombre }}</option>    
                        @endforeach
                    </select>
                    <label class="form-label">Fecha de la reserva</label>
                    <input type="date" class="form-control" name="reservas[${counter{{ $local->ID_Local }} }][fecha]" required>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Hora inicio</label>
                            <select class="form-select" name="reservas[${counter{{ $local->ID_Local }} }][horaInicio]" required>
                                @for ($i = 8; $i <= 20; $i += 0.5)
                                    @php
                                        $hora = intval($i);
                                        $minutos = ($i - $hora) * 60;
                                        $horaFormateada = sprintf("%02d:%02d", $hora, $minutos);
                                    @endphp
                                    <option value="{{ $horaFormateada }}">{{ $horaFormateada }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora fin</label>
                            <select class="form-select" name="reservas[${counter{{ $local->ID_Local }} }][horaFin]" required>
                                @for ($i = 8; $i <= 20; $i += 0.5)
                                    @php
                                        $hora = intval($i);
                                        $minutos = ($i - $hora) * 60;
                                        $horaFormateada = sprintf("%02d:%02d", $hora, $minutos);
                                    @endphp
                                    <option value="{{ $horaFormateada }}">{{ $horaFormateada }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger mt-2 remove-reserva">Eliminar</button>
                `;
                container.appendChild(newGroup);
    
                // Agregar evento para eliminar el grupo de canchas
                newGroup.querySelector('.remove-reserva').addEventListener('click', function() {
                    container.removeChild(newGroup);
                });
            });
            document.querySelectorAll('.remove-reserva').forEach(button => {
                button.addEventListener('click', function() {
                    const group = this.closest('.cancha-group');
                    group.parentNode.removeChild(group);
                });
            });
        @endforeach
    </script>


@endsection