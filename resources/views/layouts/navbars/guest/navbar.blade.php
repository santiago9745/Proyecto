<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
                <div class="container-fluid">
                    <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="{{ route('home.index') }}">
                        Inicio
                    </a>
                    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon mt-2">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>
                    <div class="navbar-nav justify-content-end">
                        <ul class="navbar-nav mx-auto">
                            @if (!Auth::check())
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
                            @else
                            <li class="nav-item">
                                <form role="form" method="post" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="nav-link me-2">
                                        <i class="fas fa-key opacity-6 text-dark me-1"></i>
                                        Cerrar sesion
                                    </a>
                                </form>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link me-2" href="{{ route('login') }}">
                                    <i class="fas fa-key opacity-6 text-dark me-1"></i>
                                    Reservas activas
                                </a>
                            </li> --}}
                            {{-- <li class="nav-item ms-auto me-3">
                                <form action="{{ route('buscar') }}" method="POST" class="d-flex align-items-right">
                                    @csrf
                                    <input type="text" name="nombre" class="form-control flex-grow-1 me-2">
                                    <button type="submit" style="border: none; background-color: transparent"><i class="fas fa-search opacity-6 text-dark me-1"></i></button>
                                </form>
                            </li> --}}
                            @endif
                            
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
