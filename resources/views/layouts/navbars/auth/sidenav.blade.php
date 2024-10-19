<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home.index') }}">
            <img src="./img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
            @if (auth()->user()->rol == 'admin')
                <span class="ms-1 font-weight-bold">Administrador</span>
            @else
                <span class="ms-1 font-weight-bold">Local</span>
            @endif
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="{{ route('profile') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Informacion de perfil</span>
                </a>
            </li>
            @if (auth()->user()->rol == 'admin')
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'user-management') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'user-management']) }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Gestion de usuarios</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'locales') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'locales']) }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Locales</span>
                </a>
            </li>
            @endif
            @if (auth()->user()->rol == 'cancha')
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'canchas') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'canchas']) }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Canchas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'contenido') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'contenido']) }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Revisar de contenido</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'reservas') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'reservas']) }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Reservas activas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'notificaciones') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'notificaciones']) }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Notificaciones</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'reportes') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'reportes']) }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Reportes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'promociones') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'promociones']) }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Promociones</span>
                    </a>
                </li>
            @endif
            @if (auth()->user()->rol == 'usuario')
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'home.index' ? 'active' : '' }}" href="{{ route('home.index') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-house-user text-primary text-sm opacity-10"></i> <!-- Icono de casita de Font Awesome -->
                        </div>
                        <span class="nav-link-text ms-1">Inicio</span> 
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'reservas') ? 'active' : '' }}" href="{{ route('page', ['page' => 'reservas']) }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i> <!-- Icono de reservas -->
                        </div>
                        <span class="nav-link-text ms-1">Reservas activas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'mapa') ? 'active' : '' }}" href="{{ route('page', ['page' => 'mapa']) }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-map-big text-dark text-sm opacity-10"></i> <!-- Icono de mapa -->
                        </div>
                        <span class="nav-link-text ms-1">Mapa de locales</span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</aside>
