@extends('layouts.app')

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Promociones'])
    <div class="container">
        <div class="row mt-4 mx-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h1>Promociones de tus Locales</h1>
                        <div class="ps-3">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregarPromocion">Agregar promocion</button>
                            
                        </div>
                        <div class="modal fade" id="ModalAgregarPromocion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Creacion de una promocion</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('crearPromocion') }}" method="POST" onsubmit="return validarFechas('fecha_inicio1', 'fecha_fin1')">
                                        @csrf
                                        
                                        <!-- Campo de descuento -->
                                        <div class="mb-3">
                                            <label for="descuento" class="form-label">Descuento (%)</label>
                                            <input type="number" class="form-control" id="descuento" name="descuento" step="0.01" min="0" required>
                                        </div>
                                    
                                        <!-- Fechas: Inicio y Fin -->
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                                <input type="date" class="form-control" id="fecha_inicio1" name="fecha_inicio" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                                <input type="date" class="form-control" id="fecha_fin1" name="fecha_fin" required>
                                            </div>
                                        </div>
                                    
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Guardar Promoción</button>
                                        </div>
                                    </form>
                                    
                                </div>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Descuento</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha Inicio</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha Fin</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cancha Asignada con el descuento</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="text-align: center">Acciones</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Promocion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sql as $promocion)
                                    <tr>
                                        <td><p class="text-sm font-weight-bold mb-0">{{ $promocion->descuento }} %</p></td>
                                        <td><p class="text-sm font-weight-bold mb-0">{{ $promocion->Fecha_Inicio }}</p></td>
                                        <td><p class="text-sm font-weight-bold mb-0">{{ $promocion->Fecha_Fin }}</p></td>
                                        <td><p class="text-sm font-weight-bold mb-0">{{ $promocion->nombre }}</p></td>
                                        <td>
                                            <div class="d-flex justify-content-start">
                                                <button type="button" class="btn btn-warning me-3" data-bs-toggle="modal" data-bs-target="#ModalEditarPromocion{{$promocion->ID_Precio}}">Editar</button>
                                                <div class="modal fade" id="ModalEditarPromocion{{$promocion->ID_Precio}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar promoción</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('EditarPromocion') }}" method="POST" onsubmit="return validarFechas('fecha_inicio2', 'fecha_fin2')">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $promocion->ID_Precio }}">
                                                                    <div class="mb-3">
                                                                        <label for="descuento" class="form-label">Descuento (%)</label>
                                                                        <input type="number" class="form-control" id="descuento" name="descuento" step="0.01" min="0" required value="{{ $promocion->descuento }}">
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                                                            <input type="date" class="form-control" id="fecha_inicio2" name="fecha_inicio" required value="{{ $promocion->Fecha_Inicio }}">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                                                            <input type="date" class="form-control" id="fecha_fin2" name="fecha_fin" required value="{{ $promocion->Fecha_Fin }}">
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary">Editar Promoción</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('EliminarPromocion', $promocion->ID_Precio) }}" onclick="return confirm('¿Estás seguro de que deseas eliminar esta promoción?');" class="btn btn-danger">Eliminar</a>
                                                <button type="button" class="btn btn-secondary ms-3" data-bs-toggle="modal" data-bs-target="#ModalAsigacionPromocion{{$promocion->ID_Precio}}">Asignar una promocion</button>
                                                <div class="modal fade" id="ModalAsigacionPromocion{{$promocion->ID_Precio}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Asignar promocion</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('asiganacionPromo') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $promocion->ID_Precio }}">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label for="form-label">Nombre de la cancha:</label>
                                                                            <p style="text-align: right; padding-top: 10px">{{$promocion->descuento}}</p>
                                                                       </div>
                                                                       <div class="col-md-6">
                                                                               <div class="mb-3">
                                                                                   <label for="disabledSelect" class="form-label">Nombre del usuario a asignar</label>
                                                                                   <select id="disabledSelect" class="form-select" name="cancha">
                                                                                       @foreach($canchas as $cancha)
                                                                                           <option value="{{$cancha->ID_Cancha}}">{{$cancha->nombre}}</option>
                                                                                       @endforeach
                                                                                   </select>
                                                                                   
                                                                               </div>
                                                                       </div>
                                                                    </div>
                                                                    
                                                                    <button type="submit" class="btn btn-primary">Asignar Promoción</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <!-- Botón para notificar sobre la promoción -->
                                            <form action="{{ route('notificarPromocion', $promocion->ID_Precio) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-info">Notificar Promocion</button>
                                            </form>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validarFechas(fechaInicioId, fechaFinId) {
            const fechaInicio = document.getElementById(fechaInicioId).value;
            const fechaFin = document.getElementById(fechaFinId).value;
            
            if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                alert("La fecha de inicio no puede ser posterior a la fecha de fin.");
                return false; // Evita el envío del formulario
            }
            return true; // Permite el envío del formulario
        }
    </script>
@endsection
