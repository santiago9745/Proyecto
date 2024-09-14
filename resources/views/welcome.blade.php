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
            <div class="min-vh-45 mt-8">
                <div class="container">
                    <div class="row">
                        @foreach ($locales as $local)
                            <!-- Cada local será una columna de Bootstrap -->
                            <div class="col-md-6 col-sm-6">
                                <div class="card mb-5">
                                    <img src="{{$local->URL ? asset($local->URL): asset('img/imagen.jpg')}}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $local->nombre }}</h5>
                                        <p class="card-text">{{ $local->direccion }}</p>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar{{ $local->ID_Local }}">
                                                    Reservar Canchas
                                        </button>
                                    </div>
                                </div>
                            </div>
        
                            <!-- Modal para agregar canchas al local actual -->
                            <div class="modal fade" id="ModalAgregar{{ $local->ID_Local }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $local->ID_Local }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel{{ $local->ID_Local }}">Agregar Reservas en {{ $local->nombre }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Contenedor Scrollable -->
                                            <div style="max-height: 300px; overflow-y: auto;">
                                                <form id="reservaForm-{{ $local->ID_Local }}" method="POST" action="{{ route('reserva') }}">
                                                    @csrf
                                                    <!-- Tabla Editable -->
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Cancha</th>
                                                                <th>Fecha</th>
                                                                <th>Hora Inicio</th>
                                                                <th>Hora Fin</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="reserva-container-{{ $local->ID_Local }}">
                                                            <!-- Filas dinámicas de reservas -->
                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="button" id="add-reserva-{{ $local->ID_Local }}" class="btn btn-primary">Agregar Reserva</button>
                                            <button type="submit" id="saveReservas-{{ $local->ID_Local }}" class="btn btn-success">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> <!-- Cierra el contenedor de la fila -->
                </div> <!-- Cierra el contenedor principal -->
            </div> <!-- Cierra la página -->
        </section>
    </main>
    <style>
        .card-img-top {
            width: 100%; /* Ajusta el ancho al 100% del contenedor */
            height: 335px; /* Altura fija */
            object-fit: cover; /* Ajusta la imagen para llenar el contenedor sin distorsión */
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($locales as $local)
                let counter{{ $local->ID_Local }} = 0; // Contador para las reservas del local actual
        
                // Función para agregar una nueva fila a la tabla
                document.getElementById('add-reserva-{{ $local->ID_Local }}').addEventListener('click', function() {
                    const tableBody = document.getElementById('reserva-container-{{ $local->ID_Local }}');
                    const newRow = document.createElement('tr');
        
                    newRow.innerHTML = `
                        <td>
                            <select class="form-select" name="reservas[${counter{{ $local->ID_Local }} }][canchas]" required>
                                <option value="">Selecciona una cancha</option>
                                @foreach ($local->canchas as $cancha)
                                    <option value="{{ $cancha->ID_Cancha }}">{{ $cancha->nombre }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="date" class="form-control" name="reservas[${counter{{ $local->ID_Local }} }][fecha]" required></td>
                        <td>
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
                        </td>
                        <td>
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
                        </td>
                        <td><button type="button" class="btn btn-danger remove-reserva">Eliminar</button></td>
                    `;
                    tableBody.appendChild(newRow);
        
                    // Incrementa el contador para las próximas reservas
                    counter{{ $local->ID_Local }}++;
        
                    // Agregar el evento para eliminar la fila de reserva
                    newRow.querySelector('.remove-reserva').addEventListener('click', function() {
                        this.closest('tr').remove(); // Elimina la fila
                    });
                });
        
                // Manejar la eliminación de filas existentes
                document.querySelectorAll('.remove-reserva').forEach(button => {
                    button.addEventListener('click', function() {
                        const row = this.closest('tr');
                        row.parentNode.removeChild(row);
                    });
                });
    
                // Agregar evento de guardar reservas
                document.getElementById('saveReservas-{{ $local->ID_Local }}').addEventListener('click', function() {
                    document.getElementById('reservaForm-{{ $local->ID_Local }}').submit(); // Enviar el formulario
                });
            @endforeach
        });
    </script>
@endsection