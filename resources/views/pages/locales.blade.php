@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Locales'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Locales</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="ps-4">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Agregar local</button>
                        
                    </div>
                    <div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edicion de local</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route("cancha.create")}}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="nombre">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Direccion</label>
                                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="direccion">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submitt" class="btn btn-primary">Agregar</button>
                                    </div>
                                </form>
                            </div>
                            
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <table class="table align-items-center mb-0 p-0">
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dirrecion</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Editar/Eliminar</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Asignar un usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sql as $row)
                            <tr>
                                <td class="align-middle text-center text-sm">
                                    <p class="text-sm font-weight-bold mb-0">{{$row->nombre}}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <p class="text-sm font-weight-bold mb-0">{{$row->direccion}}</p>
                                </td>
                                <td align="center">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6 p-0">
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalEditar{{$row->ID_Local}}">Editar</button>
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <p class="text-sm font-weight-bold mb-0 ps-0"><a href="{{route("local.delete",$row->ID_Local)}}" onclick="return res()" class="btn btn-danger">Eliminar</a></p>
                                            </div>
                                        </div>
                                    </div>
                                        
                                            
                                    <div class="card-body px-0 pt-0 pb-2">
                                        <div class="ps-4">
                                            
                                        </div>
                                        <div class="modal fade" id="ModalEditar{{$row->ID_Local}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edicion de local</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route("local.update")}}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <input type="hidden" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="id" value="{{$row->ID_Local}}">
                                                            <label class="form-label">Nombre</label>
                                                            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="nombre" value="{{$row->nombre}}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Direccion</label>
                                                            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="direccion" value="{{$row->direccion}}">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                            <button type="submitt" class="btn btn-primary">Modificar registro</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ModalAsignar">Asignar un usuario</button>
                                    <div class="modal fade" id="ModalAsignar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edicion de local</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route("cancha.asignacion")}}" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                             <label for="form-label">Nombre de la cancha:</label>
                                                             <p style="text-align: right; padding-top: 10px">{{$row->nombre}}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="disabledSelect" class="form-label">Nombre del usuario a asignar</label>
                                                                    <select id="disabledSelect" class="form-select" name="local">
                                                                        @foreach($usuarios as $usuario)
                                                                            <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submitt" class="btn btn-primary">Asignar</button>
                                                    </div>
                                                </form>
                                            </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        const dropdownButton = document.getElementById('dropdownMenuButton1');
        const dropdownItems = document.querySelectorAll('.dropdown-item');

        dropdownItems.forEach(item => {
        item.addEventListener('click', (event) => {
            dropdownButton.textContent = event.target.textContent;
        });
        });

    </script>
@endsection