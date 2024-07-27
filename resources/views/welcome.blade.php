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

    <main>
        <section>
            <div class="page-header min-vh-45">
                <form role="form" method="post" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="nav-link text-white font-weight-bold px-0">
                        <i class="fa fa-user me-sm-1"></i>
                        <span class="d-sm-inline d-none">Log out</span>
                        <h1>bienvenido al proyecto</h1>
                    </a>
                </form>
            </div>    
        </section>
    </main>
@endsection