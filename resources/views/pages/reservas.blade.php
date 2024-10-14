@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Reservas registradas'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <style>
        #mi_mapa {
            height: 400px;
            width: 750px;
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
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>        
        </div>
    </div>
    
    <!-- Modal de información de la reserva -->
    <div class="modal fade" id="modalInfoReserva" tabindex="-1" aria-labelledby="modalInfoReservaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoReservaLabel">Detalles de la Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="infoReservaContent">
                    @if (empty(auth()->user()->local))
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalDireccion">
                            <i class="fas fa-map-marker-alt"></i> Ubicacion del local
                        </button>  
                    @endif 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            events: [
                @foreach($sql as $row)
                {
                    title: `@if (!empty(auth()->user()->local))
                                {{ $row->usuario_nombre }} {{ $row->primerApellido }} {{ $row->segundoApellido }}
                            @else
                                {{ $row->nombre }}
                            @endif`,
                    start: '{{ $row->Fecha_Reserva }}T{{ $row->Hora_Inicio }}',
                    end: '{{ $row->Fecha_Reserva }}T{{ $row->Hora_Fin }}',
                    color: '{{ $row->Estado_Reserva == "Confirmada" ? "green" : ($row->Estado_Reserva == "Pendiente" ? "orange" : "red") }}',
                    extendedProps: {
                        @if (empty(auth()->user()->local))
                            nombreLocal: '{{ $row->nombre }}',
                        @endif
                        fechaReserva: '{{ $row->Fecha_Reserva }}',
                        horaInicio: '{{ $row->Hora_Inicio }}',
                        horaFin: '{{ $row->Hora_Fin }}',
                        estadoReserva: '{{ $row->Estado_Reserva }}',
                        @if (!empty(auth()->user()->local)) 
                            nombreCliente: '{{ $row->usuario_nombre }}',
                            emailCliente: '{{ $row->usuario_email }}',
                        @endif
                        @if (empty(auth()->user()->local))
                            latitud: '{{ $row->latitud }}',
                            longitud: '{{ $row->longitud }}',
                        @endif
                        idReserva: '{{ $row->ID_Reserva }}' // Agregamos el ID de la reserva aquí
                    }
                },
                @endforeach
            ],
            eventClick: function(info) {
                var props = info.event.extendedProps;

                // Mostrar los detalles de la reserva
                document.getElementById('infoReservaContent').innerHTML = `
                    ${props.nombreLocal ? `<p><strong>Nombre del Local:</strong> ${props.nombreLocal}</p>` : ''}
                    <p><strong>Fecha de Reserva:</strong> ${props.fechaReserva}</p>
                    <p><strong>Hora de Inicio:</strong> ${props.horaInicio}</p>
                    <p><strong>Hora de Fin:</strong> ${props.horaFin}</p>
                    <p><strong>Estado de Reserva:</strong> ${props.estadoReserva}</p>
                    ${!props.nombreLocal ? `<p><strong>Nombre del Cliente:</strong> ${props.nombreCliente}</p>` : ''}
                    ${!props.nombreLocal ? `<p><strong>Email del Cliente:</strong> ${props.emailCliente}</p>` : ''}
                `;

                // Sección de formulario y WhatsApp
                document.getElementById('infoReservaContent').innerHTML += `
                    @if (!empty(auth()->user()->local))
                        <form action="/reservas/${props.idReserva}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="Estado_Reserva" class="form-control" onchange="this.form.submit()">
                                <option value="Pendiente" ${props.estadoReserva === 'Pendiente' ? 'selected' : ''}>Pendiente</option>
                                <option value="Confirmada" ${props.estadoReserva === 'Confirmada' ? 'selected' : ''}>Confirmada</option>
                                <option value="Cancelada" ${props.estadoReserva === 'Cancelada' ? 'selected' : ''}>Cancelada</option>
                            </select>
                        </form>
                        <a href="https://wa.me/591${props.telefonoCliente}?text=Hola%20${encodeURIComponent(props.nombreCliente)},%20gracias%20por%20reservar%20con%20nosotros.%20Tu%20reserva%20es%20para%20el%20${props.fechaReserva}%20a%20las%20${props.horaInicio}" 
                            target="_blank" 
                            class="btn btn-success mt-2">
                            Comunícate por WhatsApp
                        </a>
                    @else
                        <p><strong>Estado actual:</strong> ${props.estadoReserva}</p>
                        ${props.latitud && props.longitud ? `<div id="mi_mapa" data-lat="${props.latitud}" data-lng="${props.longitud}"></div>` : ''}
                        ${!props.latitud && !props.longitud ? `<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalDireccion">
                            <i class="fas fa-map-marker-alt"></i> Ubicación del local
                        </button>` : ''}
                    @endif
                `;

                // Abrir el modal
                var modal = new bootstrap.Modal(document.getElementById('modalInfoReserva'));
                modal.show();

                // Si hay latitud y longitud, inicializar el mapa
                if (props.latitud && props.longitud) {
                    document.getElementById('modalInfoReserva').addEventListener('shown.bs.modal', function () {
                        var lat = document.getElementById('mi_mapa').getAttribute('data-lat');
                        var lng = document.getElementById('mi_mapa').getAttribute('data-lng');

                        var map = L.map('mi_mapa').setView([lat, lng], 15); // Zoom a 15
                        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(map);

                        L.marker([lat, lng]).addTo(map)
                            .bindPopup('Ubicación registrada.')
                            .openPopup();

                        map.invalidateSize();
                    });
                }
            }
        });

        calendar.render();
    });
    </script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endsection
