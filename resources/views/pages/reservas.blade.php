@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Reservas registradas'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #mi_mapa{
            height: 400px;
            width: 750px ;
        }
        .tooltip-inner {
        max-width: 300px; /* Ancho máximo */
        font-size: 14px;  /* Tamaño de fuente más grande */
        white-space: pre-line; /* Respeta los saltos de línea */
    }
    </style>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Reservas registradas</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    @if (empty(auth()->user()->local))
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre del local</th>
                                    @endif
                                    
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha de Reserva</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hora Inicio</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hora Fin</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado de la Reserva</th>
                                    @if (empty(auth()->user()->local))
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Direccion de local</th>
                                    @else
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Comunicarse con el cliente</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sql as $row)
                                @if (!empty(auth()->user()->local))
                                    <tr class="align-middle text-center" 
                                    data-bs-toggle="tooltip" 
                                    title="Nombre completo: {{ $row->usuario_nombre }} {{ $row->primerApellido }} {{ $row->segundoApellido }}&#10;Correo: {{ $row->usuario_email }}">
                                @else
                                    <tr>
                                @endif

                                        @if (empty(auth()->user()->local))
                                            <td class="align-middle text-center">
                                                <span class="text-xs font-weight-bold">{{ $row->nombre}}</span>
                                            </td>
                                        @endif
                                        <td class="align-middle text-center">
                                            <span class="text-xs font-weight-bold">{{ $row->Fecha_Reserva }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-xs font-weight-bold">{{ $row->Hora_Inicio }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-xs font-weight-bold">{{ $row->Hora_Fin }}</span>
                                        </td>
                                        @if (!empty(auth()->user()->local))
                                            <td class="align-middle text-center">
                                                <form action="{{ route('reservas.update', $row->ID_Reserva) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="Estado_Reserva" class="form-control" onchange="this.form.submit()">
                                                        <option value="Pendiente" {{ $row->Estado_Reserva == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                        <option value="Confirmada" {{ $row->Estado_Reserva == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                                                        <option value="Cancelada" {{ $row->Estado_Reserva == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <!-- Botón para abrir WhatsApp con el cliente -->
                                                <a href="https://wa.me/59179707164?text=Hola%20{{ urlencode($row->usuario_nombre) }},%20gracias%20por%20reservar%20con%20nosotros.%20Tu%20reserva%20es%20para%20el%20{{ $row->Fecha_Reserva }}%20a%20las%20{{ $row->Hora_Inicio }}" 
                                                   target="_blank" 
                                                   class="btn btn-success">
                                                   Comunícate por WhatsApp
                                                </a>
                                            </td>
                                        @else
                                            <td class="align-middle text-center">
                                                <span class="text-xs font-weight-bold">{{ $row->Estado_Reserva }}</span>
                                            </td>
                                        @endif
                                        <td class="aling-middle text-center">
                                            @if (empty(auth()->user()->local))
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalDireccion">
                                                    <i class="fas fa-map-marker-alt"></i> Ubicacion del local
                                                </button>  
                                            @endif 
                                            <div class="modal fade" id="ModalDireccion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Imagenes del local</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Contenedor Scrollable -->
                                                                <div id="mi_mapa" data-lat="{{ $row->latitud ?? '' }}" data-lng="{{ $row->longitud ?? '' }}"></div>
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                        </td>
                                    </tr>
                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>        
        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Inicializar el mapa
        let initialLat = parseFloat(document.getElementById('mi_mapa').getAttribute('data-lat'));
        let initialLng = parseFloat(document.getElementById('mi_mapa').getAttribute('data-lng'));
        let initialZoom = initialLat && initialLng ? 15 : 13; // Zoom más cerca si hay un marcador
    
        let map = L.map('mi_mapa').setView([initialLat || -17.392651, initialLng || -66.158681], initialZoom);
    
        // Añadir las capas del mapa
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    
        let marker;
    
        // Si ambos atributos de latitud y longitud están presentes, añadir un marcador
        if (initialLat && initialLng) {
            marker = L.marker([initialLat, initialLng]).addTo(map);
        }
    
        // Cuando el modal se muestra completamente, recalculamos el tamaño del mapa
        document.getElementById('ModalDireccion').addEventListener('shown.bs.modal', function () {
            setTimeout(function() {
                map.invalidateSize(); // Recalcula el tamaño del mapa cuando el modal está visible
            }, 100); // Pequeño retraso para asegurarse de que el modal esté completamente visible
        });
    </script>
    
@endsection
