@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Canchas'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Canchas</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if (auth()->user()->local != "")
                        <div class="ps-4">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Agregar cancha</button>
                        </div>
                    @endif
                    <div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Canchas</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('cancha.create') }}" method="POST">
                                        @csrf
                                        <div id="canchas-container">
                                            <!-- Campo inicial de cancha -->
                                            <div class="cancha-group mb-3">
                                                <label class="form-label">Nombre de la cancha</label>
                                                <input type="text" class="form-control" name="canchas[0][nombre]" required>
                                                <label class="form-label">Disponibilidad</label>
                                                <select class="form-select" name="canchas[0][disponibilidad]" required>
                                                    <option value="disponible">Disponible</option>
                                                    <option value="no disponible">No Disponible</option>
                                                </select>
                                                <label class="form-label">Tipo de deporte</label>
                                                <input type="text" class="form-control" name="canchas[0][tipo]" required>
                                                <button type="button" class="btn btn-danger mt-2 remove-cancha">Eliminar</button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="add-cancha">Agregar otra cancha</button>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Agregar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabla de canchas -->
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Disponibilidad</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipo deporte</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Editar/Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (auth()->user()->local != "")
                            @foreach ($sql as $row)
                                <tr>
                                    <td class="align-middle text-center text-sm">
                                        <p class="text-sm font-weight-bold mb-0">{{$row->nombre}}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="text-sm font-weight-bold mb-0">{{$row->estado_cancha}}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="text-sm font-weight-bold mb-0">{{$row->nombre_deporte}}</p>
                                    </td>
                                    <td align="center">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalEditar{{$row->ID_Cancha}}">Editar</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="text-sm font-weight-bold mb-0 ps-0"><a href="{{ route('cancha.delete', $row->ID_Cancha) }}" onclick="return res()" class="btn btn-danger">Eliminar</a></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="modal fade" id="ModalEditar{{$row->ID_Cancha}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edición de cancha</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('cancha.update') }}" method="POST">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <input type="hidden" class="form-control" name="id" value="{{$row->ID_Cancha}}">
                                                                    <label class="form-label">Nombre</label>
                                                                    <input type="text" class="form-control" name="nombre" value="{{$row->nombre}}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="disabledSelect" class="form-label">Disponibilidad de la cancha</label>
                                                                    <select class="form-select" name="disponibilidad">
                                                                        <option value="{{$row->estado_cancha}}">{{$row->estado_cancha}}</option>
                                                                        @if ($row->estado_cancha=='disponible')
                                                                            <option value="no disponible">NO DISPONIBLE</option>
                                                                        @else
                                                                            <option value="disponible">DISPONIBLE</option>
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tipo deporte</label>
                                                                    <input type="text" class="form-control" name="tipo" value="{{$row->nombre_deporte}}">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                    <button type="submit" class="btn btn-primary">Modificar registro</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <h1 style="text-align: center">no tiene un local asignado</h1>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let counter = 1; // Para llevar un conteo de los grupos de canchas

        document.getElementById('add-cancha').addEventListener('click', function() {
            counter++;
            const container = document.getElementById('canchas-container');
            const newGroup = document.createElement('div');
            newGroup.className = 'cancha-group mb-3';
            newGroup.innerHTML = `
                <label class="form-label">Nombre de la cancha</label>
                <input type="text" class="form-control" name="canchas[${counter}][nombre]" required>
                <label class="form-label">Disponibilidad</label>
                <select class="form-select" name="canchas[${counter}][disponibilidad]" required>
                    <option value="disponible">Disponible</option>
                    <option value="no disponible">No Disponible</option>
                </select>
                <label class="form-label">Tipo de deporte</label>
                <input type="text" class="form-control" name="canchas[${counter}][tipo]" required>
                <button type="button" class="btn btn-danger mt-2 remove-cancha">Eliminar</button>
            `;
            container.appendChild(newGroup);

            // Agregar evento para eliminar el grupo de canchas
            newGroup.querySelector('.remove-cancha').addEventListener('click', function() {
                container.removeChild(newGroup);
            });
        });

        // Manejar la eliminación de canchas existentes
        document.querySelectorAll('.remove-cancha').forEach(button => {
            button.addEventListener('click', function() {
                const group = this.closest('.cancha-group');
                group.parentNode.removeChild(group);
            });
        });
    </script>
@endsection