@extends('layouts.app')

@section('content')
<style>
     /* Oculta el div por defecto */
     .responsive-div {
        display: none;
    }

    /* Muestra el div en pantallas menores a 766px */
    @media (max-width: 992px) {
        .responsive-div {
            display: block;
        }
    }
    .escritorio-div{
        display: none;
    }
    @media (min-width: 993px){
        .escritorio-div{
            display: block;
        }
    }
    
    .table-responsive {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
}
@media (max-width: 576px) {
    .custom-alert {
        font-size: 14px;
        padding: 10px;
    }
}
    @media (max-width: 768px) {
    .carousel-inner img {
        max-height: 200px;
    }
}
/* Ajusta los márgenes en dispositivos pequeños */
@media (max-width: 768px) {
    .card {
        margin-bottom: 15px;
    }
    .scroll-container {
        max-height: 400px;
    }
}

/* Optimiza botones en pantallas pequeñas */
@media (max-width: 576px) {
    .btn {
        font-size: 12px;
        padding: 8px 10px;
    }
    .h6 {
        font-size: 14px;
    }
}
@media (max-width: 576px) {
    .fa-map-signs, .fa-globe-americas {
        font-size: 14px;
    }
    h5 {
        font-size: 18px;
    }
    p {
        font-size: 12px;
    }
}
/* En pantallas más grandes a 1024px, los divs se muestran en 6 columnas */
@media (min-width: 1120px) {
    .col-md-6 {
        width: 50%; /* 6 columnas en una fila */
    }
}

/* En pantallas menores a 1024px, los divs se muestran en 12 columnas */
@media (max-width: 1120px) {
    .col-md-6 {
        width: 100%; /* 12 columnas en una fila */
    }
}


</style>
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
            <div class="container-fluid responsive-div pt-7">
                <div class="row">
                    <div class="scroll-container" style="min-height: 30vh; max-height: 86vh; overflow-y: auto;">
                        <div class="row"> <!-- Asegúrate de tener una fila para los locales -->
                            @foreach ($locales as $local)
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="card mb-5">
                                        <div class="p-4">
                                            <div id="carousel{{ $local->ID_Local }}" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner" style="min-height: 300px;">
                                                    @if (count($local->imagenes) > 0)
                                                        @foreach ($local->imagenes as $index => $imagen)
                                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                <img src="{{ $imagen->URL }}" alt="Imagen del local" class="img-fluid" style="max-height: 300px; width: 100%; object-fit: cover;">
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="carousel-item active">
                                                            <img src="{{ asset('img/imagen.jpg') }}" alt="Imagen por defecto" style="max-height: 300px; width: 100%; object-fit: cover;">
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
                                                    <h5>{{ $local->nombre }}</h5>
                                                    <p><strong>Propietario:</strong> {{ $local->nombreCompleto }}</p>
                                                    <p><strong>Teléfono:</strong> {{ $local->telefono }}</p>
                                                    <div style="display: flex; justify-content: center; align-items: center; gap: 20px; text-align: center;">
                                                        <p><strong>Hora de apertura</strong><br> {{ $local->Hora_Apertura }}</p>
                                                        <p><strong>Hora de cierre</strong><br> {{ $local->Hora_Cierre }}</p>
                                                    </div>
                                                    <hr class="my-4">
                                                    <div class="h6 font-weight-light">
                                                        <i class="fas fa-map-signs text-muted"></i> {{ $local->direccion }}
                                                    </div>
                                                    
                                                    <div class="h6 mt-2">
                                                        <i class="fas fa-globe-americas text-muted"></i> Coordenadas: 
                                                        <span class="text-muted">{{ $local->latitud }}, {{ $local->longitud }}</span>
                                                    </div>
                                                    <div class="mt-4">
                                                        <a href="{{route('getCanchaByLocalId', $local->ID_Local)}}" class="btn btn-sm btn-info">Reservar Canchas</a>
                                                        {{-- <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar{{ $local->ID_Local }}">
                                                            Reservar Canchas
                                                        </button> --}}
                                                        <a href="https://maps.google.com/?q={{ $local->latitud }},{{ $local->longitud }}" target="_blank" class="btn btn-sm btn-success">Ver en el mapa</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>

                                <!-- Modal para agregar reservas -->
                                <div class="modal fade" id="ModalAgregar{{ $local->ID_Local }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $local->ID_Local }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel{{ $local->ID_Local }}">Agregar Reservas en {{ $local->nombre }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div style="max-height: 400px; overflow-y: auto;">
                                                    <form id="reservaForm-{{ $local->ID_Local }}" method="POST" action="{{ route('reserva') }}">
                                                        @csrf
                                                        <table class="table table-bordered table-responsive">
                                                            <thead>
                                                                <tr>
                                                                    <th>Cancha</th>
                                                                    <th>Precio</th>
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
                                                <div>
                                                    <strong>Total: </strong><span id="total-{{ $local->ID_Local }}">0.00</span>
                                                </div>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="button" id="add-reserva-{{ $local->ID_Local }}" class="btn btn-primary">Agregar Reserva</button>
                                                <button type="submit" id="saveReservas-{{ $local->ID_Local }}" class="btn btn-success">Guardar</button>
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
                <div class="container escritorio-div pt-8">
                    <div class="row">
                        <div class="scroll-container" style="min-height: 30vh; max-height: 80vh; overflow-y: auto;">
                            <div class="row"> <!-- Asegúrate de tener una fila para los locales -->
                                @foreach ($locales as $local)
                                    <div class="col-md-6 col-sm-6">
                                        <div class="card mb-5">
                                            <div class="p-4">
                                                <div id="carousel{{ $local->ID_Local }}" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-inner" style="min-height: 300px;">
                                                        @if (count($local->imagenes) > 0)
                                                            @foreach ($local->imagenes as $index => $imagen)
                                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                    <img src="{{ $imagen->URL }}" alt="Imagen del local" class="img-fluid"style="max-height: 300px; width: 100%">
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="carousel-item active">
                                                                <img src="{{ asset('img/imagen.jpg') }}" alt="Imagen por defecto" style="max-height: 300px; width: 100%;">
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
                                                        <h5>{{ $local->nombre }}</h5>
                                                        <p><strong>Propietario:</strong> {{ $local->nombreCompleto }}</p>
                                                        <p><strong>Teléfono:</strong> {{ $local->telefono }}</p>
                                                        <div style="display: flex; justify-content: center; align-items: center; gap: 20px; text-align: center;">
                                                            <p><strong>Hora de apertura</strong><br> {{ $local->Hora_Apertura }}</p>
                                                            <p><strong>Hora de cierre</strong><br> {{ $local->Hora_Cierre }}</p>
                                                        </div>
                                                        <hr class="my-4">
                                                        <div class="h6 font-weight-light">
                                                            <i class="fas fa-map-signs text-muted"></i> {{ $local->direccion }}
                                                        </div>
                                                        
                                                        <div class="h6 mt-2">
                                                            <i class="fas fa-globe-americas text-muted"></i> Coordenadas: 
                                                            <span class="text-muted">{{ $local->latitud }}, {{ $local->longitud }}</span>
                                                        </div>
                                                        <div class="mt-4">
                                                            <a href="{{route('getCanchaByLocalId', $local->ID_Local)}}" class="btn btn-sm btn-info">Reservar Canchas</a>
                                                            {{-- <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar{{ $local->ID_Local }}">
                                                                Reservar Canchas
                                                            </button> --}}
                                                            <a href="https://maps.google.com/?q={{ $local->latitud }},{{ $local->longitud }}" target="_blank" class="btn btn-sm btn-success">Ver en el mapa</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
    
                                    <!-- Modal para agregar reservas -->
                                    <div class="modal fade" id="ModalAgregar{{ $local->ID_Local }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $local->ID_Local }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel{{ $local->ID_Local }}">Agregar Reservas en {{ $local->nombre }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div style="max-height: 400px; overflow-y: auto;">
                                                        <form id="reservaForm-{{ $local->ID_Local }}" method="POST" action="{{ route('reserva') }}">
                                                            @csrf
                                                            <table class="table table-bordered table-responsive">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Cancha</th>
                                                                        <th>Precio</th>
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
                                                    <div>
                                                        <strong>Total: </strong><span id="total-{{ $local->ID_Local }}">0.00</span>
                                                    </div>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="button" id="add-reserva-{{ $local->ID_Local }}" class="btn btn-primary">Agregar Reserva</button>
                                                    <button type="submit" id="saveReservas-{{ $local->ID_Local }}" class="btn btn-success">Guardar</button>
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
            let counter{{ $local->ID_Local }} = 0;

            document.getElementById('add-reserva-{{ $local->ID_Local }}').addEventListener('click', function() {
                const tableBody = document.getElementById('reserva-container-{{ $local->ID_Local }}');
                const newRow = document.createElement('tr');

                newRow.innerHTML = `
                    <td>
                        <select class="form-select" name="reservas[${counter{{ $local->ID_Local }} }][canchas]" required>
                            <option value="">Selecciona una cancha</option>
                            @foreach ($local->canchas as $cancha)
                                <option value="{{ $cancha->ID_Cancha }}" data-precio="{{ $cancha->precio }}">{{ $cancha->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="precio-cancha">0.00</td>
                    <td><input type="date" class="form-control" name="reservas[${counter{{ $local->ID_Local }} }][fecha]" required></td>
                    <td>
                        <select class="form-select hora-inicio" name="reservas[${counter{{ $local->ID_Local }} }][horaInicio]" required>
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
                        <select class="form-select hora-fin" name="reservas[${counter{{ $local->ID_Local }} }][horaFin]" required>
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
                counter{{ $local->ID_Local }}++;

                const selectCancha = newRow.querySelector('select[name="reservas[' + (counter{{ $local->ID_Local }} - 1) + '][canchas]"]');
                const precioCancha = newRow.querySelector('.precio-cancha');

                const updatePriceAndTotal = () => {
                    const precioPorHora = parseFloat(selectCancha.options[selectCancha.selectedIndex].dataset.precio) || 0;
                    console.log('Precio de la cancha:', precioPorHora); // Verifica el precio
                    const horaInicio = newRow.querySelector('.hora-inicio');
                    const horaFin = newRow.querySelector('.hora-fin');

                    if (horaInicio.value && horaFin.value) {
                        const inicio = parseFloat(horaInicio.value.split(':')[0]) + (parseFloat(horaInicio.value.split(':')[1]) / 60);
                        const fin = parseFloat(horaFin.value.split(':')[0]) + (parseFloat(horaFin.value.split(':')[1]) / 60);
                        const duration = fin - inicio;

                        const totalPrecio = duration > 0 ? (duration * precioPorHora).toFixed(2) : 0;
                        precioCancha.textContent = totalPrecio;
                    } else {
                        precioCancha.textContent = "0.00";
                    }
                    recalculateTotal(); // Recalcula el total
                };

                function recalculateTotal() {
                    let total = 0;
                    const reservas = document.querySelectorAll('#reserva-container-{{ $local->ID_Local }} tr');
                    reservas.forEach(function(reserva) {
                        const precioCancha = parseFloat(reserva.querySelector('.precio-cancha').textContent) || 0;
                        total += precioCancha; // Sumar el precio al total
                    });
                    console.log('Total calculado:', total); // Verifica el total calculado
                    document.getElementById('total-{{ $local->ID_Local }}').textContent = total.toFixed(2);
                }

                selectCancha.addEventListener('change', updatePriceAndTotal);
                newRow.querySelector('.hora-inicio').addEventListener('change', updatePriceAndTotal);
                newRow.querySelector('.hora-fin').addEventListener('change', updatePriceAndTotal);

                newRow.querySelector('.remove-reserva').addEventListener('click', function() {
                    newRow.remove();
                    recalculateTotal(); // Recalcula el total al eliminar una reserva
                });
            });

            // Agregar evento de guardar reservas
            document.getElementById('saveReservas-{{ $local->ID_Local }}').addEventListener('click', function() {
                // Validar que no haya precios de canchas en 0
                const precios = Array.from(document.querySelectorAll('#reserva-container-{{ $local->ID_Local }} .precio-cancha'));
                const hayPrecioCero = precios.some(precio => parseFloat(precio.textContent) === 0.00);
                
                if (hayPrecioCero) {
                    alert('Error: Por favor, asegúrate de que todas las horas estén seleccionadas para las canchas.');
                } else {
                    document.getElementById('reservaForm-{{ $local->ID_Local }}').submit(); // Enviar el formulario
                }
            });
        @endforeach
    });
       // Función para cambiar las clases de todos los divs generados dinámicamente
       
</script>


    </script>
@endsection
