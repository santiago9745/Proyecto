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
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Agregar Canchas</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div style="max-height: 300px; overflow-y: auto;">
                                    <!-- Tabla editable -->
                                        <form id="canchasForm" method="POST" action="{{ route('cancha.create') }}">
                                            @csrf
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre de la Cancha</th>
                                                        <th>Disponibilidad</th>
                                                        <th>Tipo de Deporte</th>
                                                        <th>Precio de la Cancha (Bs.)</th> <!-- Nuevo campo de precio -->
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="canchas-table">
                                                    <!-- Aquí se agregan dinámicamente las filas -->
                                                </tbody>
                                            </table>
                                            
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="button" id="addRow" class="btn btn-primary">Agregar Cancha</button>
                                    <button type="button" id="saveCanchas" class="btn btn-success">Guardar</button>
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
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Precio de la cancha en Bs.</th>
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
                                    <td class="align-middle text-center text-sm">
                                        <p class="text-sm font-weight-bold mb-0">{{$row->precio}}</p>
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
                                            <!-- Modal para editar cancha -->
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
                                                                <div class="mb-3">
                                                                    <label class="form-label">Precio de la cancha</label>
                                                                    <input type="number" class="form-control" name="precio" value="{{$row->precio}}"> <!-- Campo de precio agregado -->
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
        let counter = 0; // Contador para las filas

        // Función para agregar una nueva fila a la tabla
        document.getElementById('addRow').addEventListener('click', function() {
            const tableBody = document.getElementById('canchas-table');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td><input type="text" name="canchas[${counter}][nombre]" class="form-control" required></td>
                <td>
                    <select name="canchas[${counter}][disponibilidad]" class="form-control" required>
                        <option value="disponible">Disponible</option>
                        <option value="no disponible">No Disponible</option>
                    </select>
                </td>
                <td><input type="text" name="canchas[${counter}][tipo]" class="form-control" required></td>
                <td><input type="number" name="canchas[${counter}][precio]" class="form-control" required></td>
                <td><button type="button" class="btn btn-danger remove-row">Eliminar</button></td>
            `;
            tableBody.appendChild(newRow);

            counter++;

            // Agregar el evento para eliminar la fila
            newRow.querySelector('.remove-row').addEventListener('click', function() {
                this.closest('tr').remove();
            });
        });

        // Función para guardar las canchas
        document.getElementById('saveCanchas').addEventListener('click', function() {
            document.getElementById('canchasForm').submit(); // Enviar el formulario
        });
    </script>
@endsection