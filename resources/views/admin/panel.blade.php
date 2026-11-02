@extends('layout.index')

@section('content')
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark text-white min-vh-100">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-start" id="menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.genres') }}" class="nav-link px-0 align-middle">
                            <i class="bi-tags"></i> <span class="ms-1">Жанры</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.halls') }}" class="nav-link px-0 align-middle">
                            <i class="bi bi-building"></i> <span class="ms-1">Залы</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.movies') }}" class="nav-link px-0 align-middle">
                            <i class="bi-film"></i> <span class="ms-1">Фильмы</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col py-3">
            @yield('admin-content')
        </div>
    </div>
</div>
@endsection

