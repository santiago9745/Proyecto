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
                                                        <form id="formCancha" action="" method="POST">
                                                            @csrf
                                                            <!-- Campo para la fecha -->
                                                            <label for="fechaReserva" class="mr-2 ms-5">Seleccionar Fecha:</label>
                                                            <div class="form-group d-flex justify-content-center">
                                                                
                                                                <input type="date" id="fechaReserva" name="fechaReserva" class="form-control w-50" required>
                                                            </div>
                                                            <div class="mt-4">
                                                                <h6 class="text-center">Horarios Disponibles</h6>
                                                                <table class="table table-striped text-center mt-2" id="tablaHorarios">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Hora</th>
                                                                            <th>Disponibilidad</th>
                                                                            <th>Reservar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <!-- Las filas de horarios se llenarán dinámicamente -->
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
    
    <style>
        .card-img-top {
            width: 100%; /* Ajusta el ancho al 100% del contenedor */
            height: 335px; /* Altura fija */
            object-fit: cover; /* Ajusta la imagen para llenar el contenedor sin distorsión */
        }
    </style>

@endsection
