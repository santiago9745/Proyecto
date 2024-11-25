<style>
/* Ocultar el div en pantallas grandes */
@media (min-width: 1024px) {
    #navigation {
        display: none !important; /* Añadir !important para asegurarte que esta regla prevalezca */
    }
}
/* Ocultar el botón por defecto */


/* Opcional: Puedes agregar más reglas para pantallas más grandes si lo deseas */

</style>
<!-- Font Awesome (para íconos) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
    .notificacion-mensaje {
        max-width: 400px; /* Aumenta el ancho máximo del contenedor */
        width: 400px;
        word-wrap: break-word; /* Permite que las palabras largas se dividan y ajusten */
        white-space: normal; /* Permite saltos de línea en el texto */
        overflow: hidden; /* Oculta cualquier contenido que exceda el área */
        text-overflow: ellipsis; /* Añade "..." al final del texto que excede */
        line-height: 1.4; /* Ajusta la altura de la línea para mejorar la legibilidad */
    }
    .btn-outline-primary {
        padding: 10px 20px; /* Aumenta el tamaño del padding */
        font-size: 16px; /* Aumenta el tamaño de la fuente */
        font-weight: bold; /* Pone el texto en negrita para hacerlo más llamativo */
    }
    .dropdown-menu {
        position: absolute !important;  /* Posicionamiento fuera del navbar */
        z-index: 1050; /* Asegúrate de que se muestre sobre el navbar */
        top: 100%; /* Asegura que se posicione justo debajo del ícono de la campanita */
        right: 0; /* Alinea el dropdown a la derecha */
        min-width: 300px; /* Ajusta el ancho si es necesario */
    }
    
    .navbar-toggler {
        z-index: 1051; /* Asegura que el toggle de la barra de navegación no se oculte por el dropdown */
    }

    /* Opcional: Ajustes para el contenedor de notificaciones */
    .dropdown-menu-end {
        right: 0 !important;
    }
    @media (max-width: 420px) {
    .custom-dropdown-menu {
        left: 50% !important; /* Centra el menú */
        transform: translateX(15%) !important; /* Ajusta el desplazamiento hacia la izquierda */
        right: auto !important; /* Evita que se alinee al borde derecho */
        min-width: 90vw; /* Asegura que el menú ocupe casi todo el ancho de la pantalla */
        max-width: 100%; /* Evita que el menú se desborde */
    }
}
@media (max-width: 1023px) { /* Cambia 768px según el rango deseado */
    .logout-item {
        display: none;
    }
}


</style>

<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
                <div class="container-fluid">
                    <div class="navbar-brand font-weight-bolder ms-lg-0 ms-3">
                        <!-- Contenido de la barra de navegación -->
                    </div>
                    @if (Auth::check())
                    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon mt-2">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse" id="navigation">
                        <ul>
                            <li class="nav-item">
                                <a class="nav-link me-2" href="{{ route('profile') }}">
                                    <span class="nav-link-text ms-1">Informacion de perfil</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-2" href="{{ route('home.index') }}">
                                    <span class="nav-link-text ms-1">Inicio</span> 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-2" href="{{ route('page', ['page' => 'reservas']) }}">
                                    <span class="nav-link-text ms-1">Reservas activas</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-2" href="{{ route('page', ['page' => 'mapa']) }}"> 
                                    <span class="nav-link-text ms-1">Mapa de locales</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <form role="form" method="post" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="nav-link me-2">
                                        <i class="fas fa-key opacity-6 text-dark me-1"></i>
                                        Cerrar sesión
                                    </a>
                                </form>
                            </li>

                        </ul>
                    </div>
                    @endif
                    <div class="navbar-nav justify-content-end d-flex align-items-center"> <!-- Se añadió d-flex y align-items-center aquí -->
                        <ul class="navbar-nav mx-auto">
                            <!-- Verificación de usuario no autenticado -->
                            @if (!Auth::check())
                            <div class="d-flex align-items-center">
                                <li class="nav-item">
                                    <a class="nav-link me-2" href="{{ route('register') }}">
                                        <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                                        Registrarse
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link me-2" href="{{ route('login') }}">
                                        <i class="fas fa-key opacity-6 text-dark me-1"></i>
                                        Iniciar sesion
                                    </a>
                                </li>
                            </div>
                            @else <!-- Contenedor flex para alinear horizontalmente -->
                                <!-- Botón de Notificaciones -->
                                <div class="d-flex align-items-center flex-wrap">
                                    <li class="nav-item dropdown me-3">
                                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-bell"></i>
                                            <span class="badge bg-danger ms-1">{{ $notificaciones->count() }}</span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu p-2" style="min-width: 300px;">
                                            @forelse ($notificaciones as $notificacion)
                                                <li>
                                                    <a class="dropdown-item d-flex justify-content-between align-items-center py-2" href="{{ $notificacion->data['url'] ?? '#' }}">
                                                        @php
                                                            $estado = $notificacion->data['estado'] ?? null; 
                                                        @endphp
                                                        <div class="notificacion-mensaje text-truncate" style="max-width: 200px;">
                                                            {{ $notificacion->data['mensaje'] }}
                                                            @if ($estado == 0)
                                                                Cancelada
                                                            @elseif ($estado == 1)
                                                                Confirmada
                                                            @elseif ($estado == 2)
                                                                Pendiente
                                                            @elseif ($estado == 3)
                                                                Confirmada
                                                            @else
                                                                Estado desconocido
                                                            @endif
                                                        </div>
                                                        @if (!$notificacion->read_at)
                                                            <form action="{{ route('notificaciones.marcarLeida', $notificacion->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-sm btn-outline-primary p-2 ms-2" title="Marcar como leído" data-bs-toggle="tooltip" data-bs-placement="top">
                                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </a>
                                                </li>
                                            @empty
                                                <li>
                                                    <a class="dropdown-item text-muted text-center" href="#">Sin notificaciones</a>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </li>
                                    <li class="nav-item  logout-item">
                                        <form role="form" method="post" action="{{ route('logout') }}" id="logout-form">
                                            @csrf
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                class="nav-link me-2">
                                                <i class="fas fa-key opacity-6 text-dark me-1"></i>
                                                Cerrar sesión
                                            </a>
                                        </form>
                                    </li>
                                </div>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
