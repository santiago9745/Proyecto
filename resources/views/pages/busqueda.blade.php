@extends('layouts.app')

@section('content')
<link id="pagestyle" href="/resources/css/app.css" rel="stylesheet">

<div class="container position-sticky z-index-sticky top-0">
  <div class="row">
    <div class="col-12">
      @include('layouts.navbars.guest.navbar')
    </div>
  </div>
</div>
<style>
    
</style>
<main>
  <section>
        <div class="page-header pt-7">
            <div class="container">
                @foreach ($sql as $row)
                    <div class="row pb-4">
                        <div class="col-md-12">
                            <div class="rectangle-container ">
                                <div class="rectangle-content">
                                    <p>{{$row->nombre}}</p>
                                    <p>{{$row->direccion}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
  </section>
</main>

@endsection