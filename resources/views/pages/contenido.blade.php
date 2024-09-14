@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Previzulizacion del contenido'])
    <div class="container-fluid py-4">
        @foreach ($locales as $row)
            <div class="row">
                <div class="col-md-8">
                        <div class="card">
                            <form role="form" method="POST" action={{ route('contenido.update',$row->ID_Local) }} enctype="multipart/form-data">
                                @csrf
                                <div class="card-header pb-0">
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0">Editar Local</p>
                                        <button type="submit" class="btn btn-primary btn-sm ms-auto">Guardar</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="text-uppercase text-sm">Informacion del Local</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Nombre del Local</label>
                                                <input class="form-control" type="text" name="nombre" value="{{$row->nombre}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Direccion</label>
                                                <input class="form-control" type="text" name="direccion" value="{{$row->direccion}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="imagen" class="form-control-label">Imagen</label>
                                                <input class="form-control" type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-profile">
                        <img src="{{ $row->URL }}" alt="Image placeholder" class="card-img-top">
                        
                        <div class="card-body pt-0">
                            <div class="text-center mt-4">
                                <h5>
                                    {{ $row->nombre }}
                                </h5>
                                {{-- <div class="h6 font-weight-300">
                                    <i class="ni location_pin mr-2"></i>Bucharest, Romania
                                </div>
                                <div class="h6 mt-4">
                                    <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                                </div>
                                <div>
                                    <i class="ni education_hat mr-2"></i>University of Computer Science
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        @endforeach
    </div>
@endsection