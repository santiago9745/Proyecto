@extends('layouts.app')

@section('content')
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.guest.navbar')
            </div>
        </div>
    </div>
    <main class="main-content mt-0 ">
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
                                        <h4 class="font-weight-bolder text-center">Registrarse</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('register.perform') }}">
                                            @csrf
                                            <div class="flex flex-col mb-3">
                                                <input type="text" name="username" class="form-control" placeholder="Username" aria-label="Name" value="{{ old('username') }}" >
                                                @error('username') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                            </div>
                                            <div class="flex flex-col mb-3">
                                                <input type="text" name="nombre" class="form-control" placeholder="Nombre" aria-label="Name" value="{{ old('nombre') }}" >
                                                @error('nombre') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                            </div>
                                            <div class="flex flex-col mb-3">
                                                <input type="text" name="primerApellido" class="form-control" placeholder="Primer Apellido" aria-label="Name" value="{{ old('primerApellido') }}" >
                                                @error('primerApellido') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                            </div>
                                            <div class="flex flex-col mb-3">
                                                <input type="text" name="segundoApellido" class="form-control" placeholder="Segundo Apellido" aria-label="Name" value="{{ old('segundoApellido') }}" >
                                                @error('segundoApellido') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                            </div>
                                            <div class="flex flex-col mb-3">
                                                <input type="email" name="email" class="form-control" placeholder="Email" aria-label="Email" value="{{ old('email') }}" >
                                                @error('email') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                            </div>
                                            <div class="flex flex-col mb-3">
                                                <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password">
                                                @error('password') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                            </div>
                                            <div class="form-check form-check-info text-start">
                                                <input class="form-check-input" type="checkbox" name="terms" id="flexCheckDefault" >
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and
                                                        Conditions</a>
                                                </label>
                                                @error('terms') <p class='text-danger text-xs'> {{ $message }} </p> @enderror
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign up</button>
                                            </div>
                                        </form>
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
        setInterval(nextImage, 4000);

    </script>
    @include('layouts.footers.guest.footer')
@endsection


