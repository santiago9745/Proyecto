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
                                    <form action="{{ route('crearPromocion') }}" method="POST">
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
                                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                                            </div>
                                        </div>
                                    
                                        <!-- Botón de envío -->
                                        <button type="submit" class="btn btn-primary">Guardar Promoción</button>
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acciones</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Promocion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sql as $promocion)
                                    <tr>
                                        <td><p class="text-sm font-weight-bold mb-0">{{ $promocion->descuento }} %</p></td>
                                        <td><p class="text-sm font-weight-bold mb-0">{{ $promocion->Fecha_Inicio }}</p></td>
                                        <td><p class="text-sm font-weight-bold mb-0">{{ $promocion->Fecha_Fin }}</p></td>
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
                                                                <form action="{{ route('EditarPromocion') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $promocion->ID_Precio }}">
                                                                    <div class="mb-3">
                                                                        <label for="descuento" class="form-label">Descuento (%)</label>
                                                                        <input type="number" class="form-control" id="descuento" name="descuento" step="0.01" min="0" required value="{{ $promocion->descuento }}">
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                                                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required value="{{ $promocion->Fecha_Inicio }}">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                                                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required value="{{ $promocion->Fecha_Fin }}">
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary">Editar Promoción</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('EliminarPromocion', $promocion->ID_Precio) }}" onclick="return confirm('¿Estás seguro de que deseas eliminar esta promoción?');" class="btn btn-danger">Eliminar</a>
                                            </div>
                                        </td>
                                        <td>
                                            <!-- Botón para notificar sobre la promoción -->
                                            <form action="{{ route('notificarPromocion', $promocion->ID_Precio) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-info">Notificar sobre Reserva</button>
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
@endsection
