@extends('layouts.app')

@section('content')
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.guest.navbar')
            </div>
        </div>
    </div>
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="image-slider">
                        <img src="/assets/img/futbol-11.jpg" alt="Imagen deportiva 1" class="img-fluid">
                        <img src="/assets/img/young-people-playing-basketball.jpg" alt="Imagen deportiva 2" class="img-fluid">
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-8 col-sm-0">
                            <div class="card card-plain card-border">
                                <div class="form-container">
                                    <div class="card-header pb-0 text-start">
                                        <h4 class="font-weight-bolder">Iniciar sesión</h4>
                                        <p class="mb-0">Ingresa tu email y contraseña para iniciar sesión</p>
                                    </div>
                                    <div class="card-body" style="background-color: white">
                                        <form role="form" method="POST" action="{{ route('login.perform') }}">
                                            @csrf
                                            @method('post')
                                            <div class="flex flex-col mb-3">
                                                <input type="text" name="login" class="form-control form-control-lg" placeholder="Email" aria-label="Email">
                                                @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                            </div>
                                            <div class="flex flex-col mb-3">
                                                <input type="password" name="password" class="form-control form-control-lg" placeholder="Contraseña" aria-label="Password">
                                                @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="remember" type="checkbox" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Acuérdate de mí</label>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Iniciar sesión</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center pt-0 px-lg-2 px-1" style="background-color: white">
                                        <p class="mb-1 text-sm mx-auto">
                                            ¿Olvidaste tu contraseña? Restablece tu contraseña
                                            <a href="{{ route('reset-password') }}" class="text-primary text-gradient font-weight-bold">aquí</a>
                                        </p>
                                    </div>
                                    <div class="card-footer text-center pt-0 px-lg-2 px-1" style="background-color: white">
                                        <p class="mb-4 text-sm mx-auto">
                                            ¿No tienes una cuenta?
                                            <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Regístrate</a>
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
@endsection
