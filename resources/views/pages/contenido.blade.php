@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Previzualización del contenido del local'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #mi_mapa {
            height: 400px;
            width: 800px;
        }
        .preview-row {
            display: none; /* Oculta la fila por defecto */
        }
    </style>
    <div class="container-fluid py-4">
        @foreach ($locales as $row)
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <form role="form" method="POST" action={{ route('contenido.update', $row->ID_Local) }} enctype="multipart/form-data">
                            @csrf
                            <div class="card-header pb-0">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0"><strong>Editar Local</strong></p>
                                    <button type="submit" class="btn btn-primary btn-sm ms-auto">Guardar</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="text-uppercase text-sm"><strong>Información del Local</strong></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Nombre del Local</label>
                                            <input class="form-control" type="text" name="nombre" value="{{ $row->nombre }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Información de Dirección</label>
                                            <input class="form-control" type="text" name="direccion" value="{{ $row->direccion }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="imagen" class="form-control-label">Imágenes</label>
                                            <input class="form-control" type="file" id="imagen" name="imagenes[]" accept="image/jpeg, image/png" multiple>
                                        </div>
                                    </div>

                                    <input type="hidden" name="latitud" id="latitude_field" value="{{ $row->latitud ?? '' }}">
                                    <input type="hidden" name="longitud" id="longitude_field" value="{{ $row->longitud ?? '' }}">
                                </div>
                            </div>
                        </form>

                        <!-- Botón para mostrar el modal con la tabla de imágenes -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                            Mostrar todas las imágenes
                        </button>

                        <!-- Modal para mostrar las imágenes en formato de tabla -->
                        <div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Imágenes del local</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div style="max-height: 500px; overflow-y: auto; padding: 10px;">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre de la Imagen</th>
                                                        <th>Vista Previa</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($imagenes_local as $imagen)
                                                        <tr>
                                                            <td>{{ $imagen->Tipo }}</td>
                                                            <td>
                                                                <!-- Botón para vista previa de la imagen -->
                                                                <button type="button" class="btn btn-info btn-sm" onclick="togglePreview('previewRow{{ $imagen->ID_Multimedia }}')">
                                                                    Vista Previa
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('eliminar.imagen', $imagen->ID_Multimedia) }}" onclick="return confirm('¿Estás seguro de que deseas eliminar esta imagen?');" class="btn btn-danger btn-sm">Eliminar</a>
                                                            </td>
                                                        </tr>
                                                        <!-- Fila para mostrar la imagen de vista previa -->
                                                        <tr class="preview-row" id="previewRow{{ $imagen->ID_Multimedia }}">
                                                            <td colspan="3" class="text-center">
                                                                <img src="{{ $imagen->URL }}" alt="Vista previa de la imagen" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <label for="example-text-input" class="form-control-label">Ubicación del Local</label>
                        <div id="mi_mapa" data-lat="{{ $row->latitud ?? '' }}" data-lng="{{ $row->longitud ?? '' }}"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-profile">
                        <!-- Carrusel de Bootstrap -->
                        <div id="carouselLocal{{ $row->ID_Local }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner" style="min-height: 300px;">
                                @foreach ($imagenes_local as $index => $imagen)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ $imagen->URL }}" class="d-block w-100 img-fluid" alt="Imagen del local" style="object-fit: contain; max-height: 300px;">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselLocal{{ $row->ID_Local }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselLocal{{ $row->ID_Local }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>

                        <div class="card-body pt-0">
                            <div class="text-center mt-4">
                                <h5>{{ $row->nombre }}</h5>
                                <p><strong>Propietario:</strong> {{ $row->nombreCompleto }}</p>
                                <p><strong>Teléfono:</strong> {{ $row->telefono }}</p>
                                <hr class="my-4">
                                <div class="h6 font-weight-light">
                                    <i class="fas fa-map-signs text-muted"></i> {{ $row->direccion }}
                                </div>
                                <div class="h6 mt-2">
                                    <i class="fas fa-globe-americas text-muted"></i> Coordenadas: 
                                    <span class="text-muted">{{ $row->latitud }}, {{ $row->longitud }}</span>
                                </div>
                                <div class="mt-4">
                                    <a href="#" class="btn btn-sm btn-info">Ver detalles</a>
                                    <a href="https://maps.google.com/?q={{ $row->latitud }},{{ $row->longitud }}" target="_blank" class="btn btn-sm btn-success">Ver en el mapa</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        let initialLat = parseFloat(document.getElementById('mi_mapa').getAttribute('data-lat'));
        let initialLng = parseFloat(document.getElementById('mi_mapa').getAttribute('data-lng'));
        let initialZoom = initialLat && initialLng ? 15 : 13;

        let map = L.map('mi_mapa').setView([initialLat || -17.392651, initialLng || -66.158681], initialZoom);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let marker;

        if (initialLat && initialLng) {
            marker = L.marker([initialLat, initialLng]).addTo(map);
        }

        map.on('click', function(e) {
            let latitude = e.latlng.lat;
            let longitude = e.latlng.lng;

            document.getElementById('latitude_field').value = latitude;
            document.getElementById('longitude_field').value = longitude;

            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker([latitude, longitude]).addTo(map);
        });

        function togglePreview(previewId) {
            const previewRow = document.getElementById(previewId);
            previewRow.style.display = (previewRow.style.display === 'none' || previewRow.style.display === '') ? 'table-row' : 'none';
        }
    </script>
@endsection
