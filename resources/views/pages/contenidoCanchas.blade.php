@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Previzualización del contenido de las canchas'])
    
    <style>
        .preview-row {
            display: none; /* Oculta la fila por defecto */
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Combobox para seleccionar la cancha -->
        <div class="card mb-3 pb-3 ps-3">
            <div class="row">
                <div class="col-md-6">
                    <label for="canchaSelect" class="form-label">Seleccionar Cancha</label>
                    <select id="canchaSelect" class="form-control" onchange="showCanchaInfo(this.value)">
                        <option value="">Seleccione una cancha</option>
                        @foreach ($canchas as $cancha)
                            <option value="{{ $cancha->ID_Cancha }}" {{ old('id') == $cancha->ID_Cancha ? 'selected' : '' }}>{{ $cancha->nombreCancha }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div id="canchaInfoContainer">
            @foreach ($canchas as $row)
                <div class="cancha-info" id="canchaInfo{{ $row->ID_Cancha }}" style="display: none;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <form role="form" method="POST" action="{{ route('contenido.updateCanchas') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $row->ID_Cancha }}">
                                    <div class="card-header pb-0">
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0"><strong>Editar Cancha</strong></p>
                                            <button type="submit" class="btn btn-primary btn-sm ms-auto">Guardar</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-uppercase text-sm"><strong>Información de la Cancha</strong></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nombre" class="form-control-label">Nombre de la Cancha</label>
                                                    <input class="form-control" type="text" name="nombre" value="{{ $row->nombreCancha }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="estado" class="form-control-label">Estado de la Cancha</label>
                                                    <select class="form-control" name="estado" id="estado">
                                                        <option value="Disponible" {{ $row->estado_cancha == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                                        <option value="No Disponible" {{ $row->estado_cancha == 'No Disponible' ? 'selected' : '' }}>No Disponible</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="imagen" class="form-control-label">Imágenes</label>
                                                    <input class="form-control" type="file" id="imagen" name="imagenes[]" accept="image/jpeg, image/png" multiple>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- Botón para mostrar el modal con la tabla de imágenes -->
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar{{ $row->ID_Cancha }}">
                                    Mostrar todas las imágenes
                                </button>

                                <!-- Modal para mostrar las imágenes en formato de tabla -->
                                <div class="modal fade" id="ModalAgregar{{ $row->ID_Cancha }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Imágenes de la Cancha</h5>
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
                                                            @foreach ($imagenes_canchas as $imagen)
                                                                @if ($imagen->ID_Cancha == $row->ID_Cancha)
                                                                    <tr>
                                                                        <td>{{ $imagen->Tipo }}</td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-info btn-sm" onclick="togglePreview('previewRow{{ $imagen->ID_Multimedia }}')">
                                                                                Vista Previa
                                                                            </button>
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('eliminar.imagen', $imagen->ID_Multimedia) }}" onclick="return confirm('¿Estás seguro de que deseas eliminar esta imagen?');" class="btn btn-danger btn-sm">Eliminar</a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="preview-row" id="previewRow{{ $imagen->ID_Multimedia }}">
                                                                        <td colspan="3" class="text-center">
                                                                            <img src="{{ $imagen->URL }}" alt="Vista previa de la imagen" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                                                                        </td>
                                                                    </tr>
                                                                @endif
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
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-profile">
                                <!-- Carrusel de Bootstrap -->
                                <div id="carouselCancha{{ $row->ID_Cancha }}" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner" style="min-height: 300px;">
                                        @foreach ($imagenes_canchas as $index => $imagen)
                                            @if ($imagen->ID_Cancha == $row->ID_Cancha)
                                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                    <img src="{{ $imagen->URL }}" class="d-block w-100 img-fluid" alt="Imagen de la cancha" style="object-fit: contain; max-height: 300px;">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselCancha{{ $row->ID_Cancha }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Anterior</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselCancha{{ $row->ID_Cancha }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Siguiente</span>
                                    </button>
                                </div>

                                <div class="card-body pt-0">
                                    <div class="text-center mt-4">
                                        <h5>{{ $row->nombreCancha }}</h5>
                                        <p><strong>Estado:</strong> {{ $row->estado_cancha }}</p>
                                        <hr class="my-4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // Función para obtener el parámetro de la URL
function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

window.onload = function() {
    const canchaIdFromUrl = getUrlParameter('id'); // Obtener el ID de la cancha de la URL

    // Llamar a la función para mostrar la información de la cancha si se encuentra un ID
    if (canchaIdFromUrl) {
        showCanchaInfo(canchaIdFromUrl);
    }
};

let lastSelectedCancha = null; // Variable para almacenar la última cancha seleccionada

function showCanchaInfo(id) {
    // Ocultar todas las secciones de información de las canchas
    const canchaInfos = document.querySelectorAll('.cancha-info');
    canchaInfos.forEach(info => info.style.display = 'none');

    // Mostrar la información de la cancha seleccionada
    if (id) {
        document.getElementById('canchaInfo' + id).style.display = 'block';
        lastSelectedCancha = id; // Actualizar la última cancha seleccionada
    } else {
        lastSelectedCancha = null; // Resetear si no hay selección
    }
}
function togglePreview(previewId) {
            const previewRow = document.getElementById(previewId);
            previewRow.style.display = (previewRow.style.display === 'none' || previewRow.style.display === '') ? 'table-row' : 'none';
        }
    </script>
    
@endsection
