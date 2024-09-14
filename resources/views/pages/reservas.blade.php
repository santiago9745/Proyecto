@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Reservas registradas'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Reservas registradas</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha de Reserva</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hora Inicio</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hora Fin</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado de la Reserva</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sql as $row)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <span class="text-xs font-weight-bold">{{ $row->Fecha_Reserva }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-xs font-weight-bold">{{ $row->Hora_Inicio }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-xs font-weight-bold">{{ $row->Hora_Fin }}</span>
                                        </td>
                                        @if (!empty(auth()->user()->local))
                                            <td class="align-middle text-center">
                                                <form action="{{ route('reservas.update', $row->ID_Reserva) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="Estado_Reserva" class="form-control" onchange="this.form.submit()">
                                                        <option value="Pendiente" {{ $row->Estado_Reserva == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                        <option value="Confirmada" {{ $row->Estado_Reserva == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                                                        <option value="Cancelada" {{ $row->Estado_Reserva == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                                    </select>
                                                </form>
                                            </td>
                                        @else
                                            <td class="align-middle text-center">
                                                <span class="text-xs font-weight-bold">{{ $row->Estado_Reserva }}</span>
                                            </td>
                                        @endif
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
