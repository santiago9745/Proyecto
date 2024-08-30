@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Previzulizacion del contenido'])
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Previsualización de contenido</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="custom-file">
                                <form action="{{ route('cancha.contenido') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label class="custom-file-label" for="customFile">Selecciona una imagen</label>
                                    <input type="file" name="imagen" class="custom-file-input" id="customFile" accept="image/*" multiple>
                                    <button type="submit" class="btn btn-primary">Subir imagen</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Contenido</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            @foreach ($sql as $row)
                                <img src="{{asset($row->URL)}}" class="img-fluid ps-5">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection