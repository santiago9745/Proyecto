@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'reservas activas'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>calendario de reservas</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">

                </div>
            </div>        
        </div>
    </div>
@endsection