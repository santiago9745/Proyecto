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
                                    <div id="carousel{{ $local->ID_Local }}" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner" style="min-height: 300px;">
                                            @if (count($local->imagenes) > 0)
                                                @foreach ($local->imagenes as $index => $imagen)
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <img src="{{ $imagen->URL }}" class="d-block w-100 img-fluid" alt="Imagen del local" style="object-fit: contain; max-height: 300px;">
                                                    </div>
                                                @endforeach
                                            @else
                                                <!-- Si no hay imágenes, muestra una imagen por defecto -->
                                                <div class="carousel-item active">
                                                    <img src="{{  asset('img/imagen.jpg') }}" class="d-block w-100 img-fluid" alt="Imagen por defecto" style="object-fit: contain; max-height: 300px;">
                                                </div>
                                            @endif
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $local->ID_Local }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $local->ID_Local }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mt-4">
                                            <!-- Nombre del local con un icono de localización -->
                                                <h5>{{ $local->nombre }}</h5> <!-- Nombre del local -->
                                                <p><strong>Propietario:</strong> {{ $local->nombreCompleto }}</p> <!-- Nombre completo del propietario -->
                                                <p><strong>Teléfono:</strong> {{ $local->telefono }}</p> <!-- Teléfono del propietario -->
                                            
                                            <!-- Divider para separación de contenido -->
                                            <hr class="my-4">
                                    
                                            <!-- Dirección con icono de dirección -->
                                            <div class="h6 font-weight-light">
                                                <i class="fas fa-map-signs text-muted"></i> {{ $local->direccion }}
                                            </div>
                                    
                                            <!-- Latitud y Longitud del local -->
                                            <div class="h6 mt-2">
                                                <i class="fas fa-globe-americas text-muted"></i> Coordenadas: 
                                                <span class="text-muted">{{ $local->latitud }}, {{ $local->longitud }}</span>
                                            </div>
                                    
                                            <!-- Botones de acción -->
                                            <div class="mt-4">
                                                <a href="#" class="btn btn-sm btn-info">Ver detalles</a>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar{{ $local->ID_Local }}">
                                                    Reservar Canchas
                                                </button>
                                                <a href="https://maps.google.com/?q={{ $local->latitud }},{{ $local->longitud }}" target="_blank" class="btn btn-sm btn-success">Ver en el mapa</a>
                                            </div>
                                        </div>
                                        
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
                                            <div style="max-height: 400px; overflow-y: auto;">
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