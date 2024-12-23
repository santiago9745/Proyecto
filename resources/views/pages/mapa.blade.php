@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        /* Hacer que el mapa sea responsivo */
        #mi_mapa {
            height: 80vh;
            width: 98%;  /* El mapa ocupará el 100% del contenedor */
        }

        /* Hacer que las imágenes en el slider sean responsivas */


        /* Asegurar que el contenedor tenga un espaciado adecuado en pantallas pequeñas */
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
    </style>

    <!-- Slider de imágenes, ahora es responsivo -->
    <div class="image-slider">
        <img src="/assets/img/futbol-11.jpg" alt="Imagen deportiva 1">
        <img src="/assets/img/young-people-playing-basketball.jpg" alt="Imagen deportiva 2">
    </div>

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
                        <div class="col-12">
                            <!-- Contenedor del mapa ahora será responsivo -->
                            <div id="mi_mapa" data-lat="" data-lng=""></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Inicializar el mapa
        let map = L.map('mi_mapa').setView([-17.392651, -66.158681], 13); // Ajusta la vista inicial según tus coordenadas
    
        // Añadir las capas del mapa
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    
        // Agregar los marcadores
        @foreach ($locales as $local)
            L.marker([{{ $local->latitud }}, {{ $local->longitud }}]).addTo(map)
            .bindPopup(`
            <div style="width: 200px;">
                <h5 style="margin: 0; color: #333;">{{ $local->nombreLocal }}</h5>
                <p style="margin: 5px 0; color: #666;">Información de ubicación: {{ $local->direccion }}</p>
                <p style="margin: 5px 0;">Teléfono: {{ $local->telefono }} </p>
                <a href="https://wa.me/591{{$local->telefono}}" target="_blank" style="color: #25D366; text-decoration: none;">Comunícate por WhatsApp</a>
                <a href="{{route('getCanchaByLocalId', $local->ID_Local)}}" class="btn btn-sm btn-info mt-3">Reservar Canchas</a>
            </div>
        `); 
        @endforeach
    </script>
@endsection
