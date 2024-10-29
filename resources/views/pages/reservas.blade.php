@extends('layouts.app')

@section('content')
@if (in_array(auth()->user()->rol, ['cancha', 'admin']))
    @include('layouts.navbars.auth.topnav', ['title' => 'Reportes'])
@else
    @include('layouts.navbars.guest.navbar')
    <div class="image-slider">
        <img src="/assets/img/futbol-11.jpg" alt="Imagen deportiva 1">
        <img src="/assets/img/young-people-playing-basketball.jpg" alt="Imagen deportiva 2">
    </div>
@endif



<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/es.global.min.js"></script>


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
    .calendar-container {
        max-height: 585px; /* Ajusta la altura según sea necesario */
        overflow-y: auto; /* Permite el desplazamiento vertical */
    }
</style>

<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body px-0 pt-7 pb-2">
                <div class="table-responsive p-0">
                    <div class="calendar-container"> <!-- Contenedor para el scroll -->
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<div class="modal fade" id="modalInfoReserva" tabindex="-1" aria-labelledby="modalInfoReservaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- Agregar modal-dialog-centered -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInfoReservaLabel">Detalles de la Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="infoReservaContent" style="max-height: 650px; overflow-y: auto; overflow-x: hidden;">
            </div>
            <div class="modal-footer">
                <p class="text-sm font-weight-bold mb-0 ps-2">
                </p>                      
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
        dayHeaderFormat: { weekday: 'long' },
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
                color: '{{ $row->Estado_Reserva == "1" ? "green" : ($row->Estado_Reserva == "2" ? "orange" : "red") }}',
                extendedProps: {
                    @if (!empty(auth()->user()->local))
                        diasRestantes: '{{ $row->dias_restantes }}', // Asegúrate de que los días restantes estén en la consulta SQL
                        horasRestantes: '{{ $row->horas_restantes }}', // Asegúrate de que las horas restantes estén en la consulta SQL
                    @endif
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
                        idUsuario: '{{ $row->id }}',
                    @endif
                    @if (empty(auth()->user()->local))
                        latitud: '{{ $row->latitud }}',
                        longitud: '{{ $row->longitud }}',
                        fechaCreacion: '{{ $row->fecha_creacion }}',
                    @endif
                    idReserva: '{{ $row->ID_Reserva }}', // Agregamos el ID de la reserva aquí
                    
                }
            },
            @endforeach
        ],
        eventClick: function(info) {
            var props = info.event.extendedProps;
            var now = new Date();

            // Obtener la fecha y hora de inicio de la reserva
            var fechaInicioReserva = new Date(info.event.start);

            // Calcular la diferencia en milisegundos
            var diferenciaMs = fechaInicioReserva - now;

            // Convertir a días y horas
            var diasRestantes = Math.floor(diferenciaMs / (1000 * 60 * 60 * 24));
            var horasRestantes = Math.floor((diferenciaMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

            // Determinar el color
            var color = diferenciaMs >= 0 ? 'green' : 'grey';

            // Mostrar los detalles de la reserva
            document.getElementById('infoReservaContent').innerHTML = `
                ${props.nombreLocal ? `<p><strong>Nombre del Local:</strong> ${props.nombreLocal}</p>` : ''}
                
                ${props.nombreCliente ? `
                    <div class="d-flex mb-2">
                        <p class="me-3"><strong>Nombre del Cliente:</strong> ${props.nombreCliente}</p>
                        <p><strong>Email del Cliente:</strong> ${props.emailCliente || 'No disponible'}</p>
                    </div>
                ` : ''}
                
                <div class="d-flex">
                    <p><strong>Fecha de Reserva:</strong> ${props.fechaReserva}</p>
                    <p class="me-3 ms-3"><strong>Hora de Inicio:</strong> ${props.horaInicio}</p>
                    <p><strong>Hora de Fin:</strong> ${props.horaFin}</p>
                </div>
            `;

            document.getElementById('infoReservaContent').innerHTML += `
                <div class="d-flex">
                    <p class="me-3"><strong>Estado de Reserva:</strong> ${props.estadoReserva}</p>
                    <p><strong>Tiempo restante:</strong>
                        ${diferenciaMs >= 0
                            ? `<span style="color: ${color};">Quedan ${diasRestantes} días y ${horasRestantes} horas</span>`
                            : `<span style="color: grey;">La reserva ya ha pasado.</span>`
                        }
                    </p>
                </div>
            `;

            // Sección de formulario y WhatsApp
            document.getElementById('infoReservaContent').innerHTML += `
            @if (!empty(auth()->user()->local))
                <form action="/reservas/${props.idReserva}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="Estado_Reserva" class="form-control" onchange="this.form.submit()">
                        <option value="2" ${props.estadoReserva === '2' ? 'selected' : ''}>Pendiente</option>
                        <option value="1" ${props.estadoReserva === '1' ? 'selected' : ''}>Confirmada</option>
                        <option value="0" ${props.estadoReserva === '0' ? 'selected' : ''}>Cancelada</option>
                    </select>
                </form>
                <div class="d-flex align-items-center">
                <a href="https://wa.me/591${props.telefonoCliente}?text=Hola%20${encodeURIComponent(props.nombreCliente)},%20gracias%20por%20reservar%20con%20nosotros.%20Tu%20reserva%20es%20para%20el%20${props.fechaReserva}%20a%20las%20${props.horaInicio}" 
                    target="_blank" 
                    class="btn btn-success mt-2 me-3 btn-sm">
                    Comunícate por WhatsApp
                </a>
                
                <form action="/comprobante" method="POST" class="d-inline me-3" target="_blank">
                    @csrf
                    <input type="hidden" name="id" value="${props.idUsuario}">
                    <input type="hidden" name="fechReserva" value="${props.fechaReserva}">
                    <button type="submit" class="btn btn-outline-success mt-3 btn-sm">
                        <i class="fas fa-chart-bar me-2"></i> Comprobante
                    </button>
                </form>
                <form action="/enviarComprobante" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="id" value="${props.idUsuario}">
                    <input type="hidden" name="fechaRserva" value="${props.fechaReserva}">
                    <input type="hidden" name="email" value="${props.emailCliente}">
                    <button type"submit" class="btn btn-outline-success mt-3 btn-sm">
                        <i class="fas fa-envelope me-2"></i>Enviar comprobante
                    </button>
                </form>
                </div>
            </a>
            @else
                ${props.latitud && props.longitud ? `<div id="mi_mapa" data-lat="${props.latitud}" data-lng="${props.longitud}"></div>` : ''}
                ${!props.latitud && !props.longitud ? `<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalDireccion">
                    <i class="fas fa-map-marker-alt"></i> Ubicación del local
                </button>` : ''}
                <div class="d-flex mt-3">
                    <form action="/reservasCancel/${props.idReserva}" method="POST" onsubmit="return confirmarCancelacion()">
                        @csrf
                        @method('PUT') <!-- Cambia a PUT si es necesario -->
                        <button type="submit" class="btn btn-danger">Cancelar Reserva</button>
                    </form>
                    <form action="{{ route('Cotizacion') }}" method="POST">
                        @csrf
                        <input type="text" name="fecha_creacion" value="${props.fechaReserva}">
                        <button type="submit" class="btn btn-outline-success mt-2">Generar Cotización</button>
                    </form>

                </div>
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
