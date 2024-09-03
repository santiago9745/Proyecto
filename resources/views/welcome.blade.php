@extends('layouts.app')

@section('content')
<link id="pagestyle" href="/resources/css/app.css" rel="stylesheet">
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
                @foreach ($sql as $row)
                <div class="container">   
                    <div class="row">
                        <div class="col-md-4">
                                <div class="card-body">
                                    <div class="square"> 
                                        
                                        <h6 class="tituloLocales">{{$row->nombre}}</h6>
                                        <p class="parrafoLocales">{{$row->direccion}}</p>
                                        <div class="ps-4">
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Agregar cancha</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Canchas</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('reserva') }}" method="POST">
                                    @csrf
                                    <div id="reserva-container">
                                        <!-- Campo inicial de reserva -->
                                        <div class="reserva-group mb-3">
                                            <input type="hidden" name="idlocal" value="{{$row->ID_Local}}">
                                            <label class="form-label">Canchas disponibles</label>
                                            <select class="form-select" name="reservas[0][canchas]" required>
                                                <option value="...">...</option>  
                                                @foreach ($canchas as $row)
                                                    <option value="{{$row->ID_Cancha}}">{{$row->nombre}}</option>    
                                                @endforeach
                                                
                                            </select>
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
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary" id="add-reserva">hacer otra reserva</button>
                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>    
        </section>
    </main>
    <script>
        let counter = 1; // Para llevar un conteo de los grupos de canchas

        document.getElementById('add-reserva').addEventListener('click', function() {
            counter++;
            const container = document.getElementById('reserva-container');
            const newGroup = document.createElement('div');
            newGroup.className = 'reserva-group mb-3';
            newGroup.innerHTML = `
                <input type="hidden" name="idlocal" value="{{$row->ID_Local}}">
                <label class="form-label">Canchas disponibles</label>
                <select class="form-select" name="reservas[${counter}][canchas]" required>
                    <option value="...">...</option>  
                    @foreach ($canchas as $row)
                        <option value="{{$row->ID_Cancha}}">{{$row->nombre}}</option>    
                    @endforeach     
                </select>
                <label class="form-label">Fecha de la reserva</label>
                <input type="date" class="form-control" name="reservas[${counter}][fecha]" required>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Hora inicio</label>
                        <select class="form-select" name="reservas[${counter}][horaInicio]" required>
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
                        <select class="form-select" name="reservas[${counter}][horaFin]" required>
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

        // Manejar la eliminación de canchas existentes
        document.querySelectorAll('.remove-reserva').forEach(button => {
            button.addEventListener('click', function() {
                const group = this.closest('.cancha-group');
                group.parentNode.removeChild(group);
            });
        });
    </script>
@endsection