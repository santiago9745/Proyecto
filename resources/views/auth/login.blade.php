@extends('layouts.app')

@section('content')
<link id="pagestyle" href="/resources/css/app.css" rel="stylesheet">
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.guest.navbar')
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>              
            <div class="page-header min-vh-100">
                <div class="container">
                        <div class="image-slider">
                            <img src="/assets/img/futbol-11.jpg" alt="Imagen deportiva 1">
                            <img src="/assets/img/young-people-playing-basketball.jpg" alt="Imagen deportiva 2">
                        </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="card card-plain card-border">
                                <div class="form-conteiner">
                                    <div class="card-header pb-0 text-start">
                                        <h4 class="font-weight-bolder">Iniciar sesión</h4>
                                        <p class="mb-0">Ingresa tu email y contraseña para iniciar sesión</p>
                                    </div>
                                    <div class="card-body">
                                        <form role="form" method="POST" action="{{ route('login.perform') }}">
                                            @csrf
                                            @method('post')
                                            <div class="flex flex-col mb-3">
                                                <input type="email" name="email" class="form-control form-control-lg" aria-label="Email">
                                                @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                            </div>
                                            <div class="flex flex-col mb-3">
                                                <input type="password" name="password" class="form-control form-control-lg" aria-label="Password" value="12345678" >
                                                @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="remember" type="checkbox" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Acuérdate de mi</label>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                        <p class="mb-1 text-sm mx-auto">
                                            ¿Olvidaste tu contraseña? Restablece tu contraseña
                                            <a href="{{ route('reset-password') }}" class="text-primary text-gradient font-weight-bold">aqui</a>
                                        </p>
                                    </div>
                                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                        <p class="mb-4 text-sm mx-auto">
                                            ¿No tienes una cuenta?
                                            <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Registrate</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
        const images = document.querySelectorAll('.image-slider img');
        let currentIndex = 0;

        function showImage(index) {
        images.forEach(image => {
            image.style.opacity = 0;
        });
        images[index].style.opacity = 1;
        }

        function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
        }

        // Mostrar la primera imagen al cargar la página
        showImage(currentIndex);

        // Cambiar de imagen cada 3 segundos
        setInterval(nextImage, 5000);

    </script>
@endsection
