@extends('layouts.app')

@section('content')
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
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain card-border">
                                <div class="form-conteiner">
                                    <div class="card-header pb-0 text-start">
                                        <h4 class="font-weight-bolder">Cambie su contrasena</h4>
                                        <p class="mb-0">Porfavor establesca una nueva contrasena para el ingreso al sistema</p>
                                    </div>
                                    <div class="card-body">
                                        <form role="form" method="POST" action="{{ route('change.perform') }}">
                                            @csrf
                                            <div class="flex flex-col mb-3">
                                                <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" value="{{ old('email') }}" aria-label="Email">
                                                @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                            </div>
                                            <div class="flex flex-col mb-3">
                                                <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" >
                                                @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                            </div>
                                            <div class="flex flex-col mb-3">
                                                <input type="password" name="confirm-password" class="form-control form-control-lg" placeholder="Password" aria-label="Password"  >
                                                @error('confirm-password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Restablecer contraseña</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="alert">
                                        @include('components.alert')
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