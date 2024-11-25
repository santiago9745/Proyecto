@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Locales'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #mi_mapa {
            height: 500px; /* Aumenta la altura */
            width: 100%;
            border: 2px solid #ccc; /* Agrega un borde */
            border-radius: 5px; /* Bordes redondeados */
        }

        .form-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .form-left {
            flex: 1;
            min-width: 300px;
        }
        .form-right {
            flex: 1;
            min-width: 400px;
        }
    </style>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Locales</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="ps-4">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Agregar local</button>
                    </div>
                    <div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg"> 
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar local</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{route('local.create')}}" method="POST">
                                        @csrf
                                        <div class="form-container">
                                            <div class="form-left">
                                                <div class="mb-3">
                                                    <label class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" name="nombre" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Dirección</label>
                                                    <input type="text" class="form-control" name="direccion" required>
                                                </div>
                                            </div>
                                            <div class="form-right">
                                                <input type="hidden" name="latitud" id="latitude_field">
                                                <input type="hidden" name="longitud" id="longitude_field">
                                                <div id="mi_mapa"></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Agregar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table align-items-center mb-0 p-0">
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dirección</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Editar/Eliminar</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Asignar un usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sql as $row)
                            <tr>
                                <td class="align-middle text-center text-sm">
                                    <p class="text-sm font-weight-bold mb-0">{{$row->nombre}}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <p class="text-sm font-weight-bold mb-0">{{$row->direccion}}</p>
                                </td>
                                <td align="center">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6 p-0">
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalEditar{{$row->ID_Local}}">Editar</button>
                                            </div>
                                            <div class="modal fade" id="ModalEditar{{$row->ID_Local}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg"> 
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar local</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{route('local.update')}}" method="POST">
                                                                @csrf
                                                                <div class="form-container">
                                                                    <div class="form-left">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Nombre</label>
                                                                            <input type="text" class="form-control" name="nombre" required value="{{$row->nombre}}">
                                                                            <input type="hidden" class="form-control" name="id" required value="{{$row->ID_Local}}">
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Dirección</label>
                                                                            <input type="text" class="form-control" name="direccion" required value="{{$row->direccion}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-right">
                                                                        <input type="text" name="latitud" id="latitude_field{{$row->ID_Local}}" value="{{$row->latitud}}">
                                                                        <input type="text" name="longitud" id="longitude_field{{$row->ID_Local}}" value="{{$row->longitud}}">
                                                                        <div id="mi_mapa{{$row->ID_Local}}" style="height: 300px;"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <p class="text-sm font-weight-bold mb-0 ps-0"><a href="{{route("local.delete",$row->ID_Local)}}" onclick="return res()" class="btn btn-danger">Eliminar</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ModalAsignar{{$row->ID_Local}}">Asignar un usuario</button>
                                    <div class="modal fade" id="ModalAsignar{{$row->ID_Local}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edición de local</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route("local.asignacion")}}" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                             <label for="form-label">Nombre de la cancha:</label>
                                                             <p style="text-align: right; padding-top: 10px">{{$row->nombre}}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="disabledSelect" class="form-label">Nombre del usuario a asignar</label>
                                                                    <select id="disabledSelect" class="form-select" name="local">
                                                                        @foreach($usuarios as $usuario)
                                                                            <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input type="text" value="{{$row->ID_Local}}" name="id" hidden>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Asignar</button>
                                                    </div>
                                                </form>
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

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    var map;
var currentMarker; // Variable to store the current marker

function initializeMap(lat, lng) {
    // Initialize the map
    map = L.map('mi_mapa').setView([lat, lng], 13);

    // Change the tile layer to a clearer one
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
    }).addTo(map);

    // Add scale control
    L.control.scale().addTo(map);

    // Event for clicking on the map
    map.on('click', function (e) {
        var lat = e.latlng.lat;  // Latitude
        var lng = e.latlng.lng;  // Longitude

        // Update inputs with coordinates
        document.getElementById('latitude_field').value = lat;
        document.getElementById('longitude_field').value = lng;

        // Remove the previous marker if it exists
        if (currentMarker) {
            map.removeLayer(currentMarker);
        }

        // Add a new marker at the selected location
        currentMarker = L.marker([lat, lng]).addTo(map);
    });
}

// Event listener for modal opening
document.addEventListener('DOMContentLoaded', function () {
    // Add modal
    var addModal = document.getElementById('ModalAgregar');
    addModal.addEventListener('shown.bs.modal', function () {
        if (map) {
            map.remove(); // Remove the old map if exists
        }
        initializeMap(-17.392651, -66.158681); // Default coordinates for adding
    });

    // Edit modal - ensure you have the same event listener structure here
    @foreach ($sql as $row)
        var editModal = document.getElementById('ModalEditar{{$row->ID_Local}}');
        editModal.addEventListener('shown.bs.modal', function () {
            if (map) {
                map.remove(); // Remove the old map if exists
            }
            initializeMap({{$row->latitud}}, {{$row->longitud}}); // Use existing coordinates
        });
    @endforeach
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Asegúrate de que hay un evento para cada modal de edición
    @foreach ($sql as $row)
        var modalEditar{{$row->ID_Local}} = document.getElementById('ModalEditar{{$row->ID_Local}}');

        modalEditar{{$row->ID_Local}}.addEventListener('shown.bs.modal', function () {
            // Elimina el mapa anterior si ya existe
            if (typeof map !== 'undefined') {
                map.remove();
            }

            // Inicializa el mapa
            var map = L.map('mi_mapa{{$row->ID_Local}}').setView([{{$row->latitud}}, {{$row->longitud}}], 13);

            // Cambia el tile layer por uno más nítido
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            }).addTo(map);

            // Agrega un marcador en la ubicación inicial del local
            var currentMarker = L.marker([{{$row->latitud}}, {{$row->longitud}}]).addTo(map);

            // Agrega controles de escala
            L.control.scale().addTo(map);

            // Evento de clic en el mapa
            map.on('click', function(e) {
                var lat = e.latlng.lat;  // Latitud
                var lng = e.latlng.lng;  // Longitud

                // Actualiza los inputs con las coordenadas
                document.getElementById('latitude_field{{$row->ID_Local}}').value = lat;
                document.getElementById('longitude_field{{$row->ID_Local}}').value = lng;

                // Elimina el marcador anterior si existe
                if (currentMarker) {
                    map.removeLayer(currentMarker);
                }

                // Agrega un nuevo marcador en la ubicación seleccionada
                currentMarker = L.marker([lat, lng]).addTo(map);
            });
        });
    @endforeach
});

</script>

    
@endsection
