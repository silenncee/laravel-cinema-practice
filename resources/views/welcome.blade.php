@extends('layout.index')

@section('title', 'Афиша')
@section('description', 'Афиша кинотеатра: фильтры по жанрам, поиск, расписание сеансов')
@section('keywords', 'кино, афиша, билеты, сеансы, жанры')

@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Афиша</h1>
        </div>

        {{-- Блок фильтров и поиска --}}
        <div class="card bg-light mb-4">
            <div class="card-body">
                <form method="get" action="{{ route('home') }}" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Поиск по названию</label>
                        <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Введите название фильма...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Жанр</label>
                        <select name="genre" class="form-select">
                            <option value="">Все жанры</option>
                            @foreach($genres as $g)
                                <option value="{{ $g->slug }}" @selected(request('genre') === $g->slug)>{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Применить</button>
                        @if(request('q') || request('genre'))
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">Сбросить</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Сетка фильмов --}}
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @forelse($movies as $m)
                <div class="col">
                    <div class="card h-100">
                        @if($m->poster_url)
                            <img src="{{ asset($m->poster_url) }}" class="card-img-top" alt="{{ $m->title }}" style="height: 300px; object-fit: cover;">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 300px;">
                                Нет постера
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $m->title }}</h5>
                            <p class="text-muted small mb-2">
                                {{ $m->age_rating }} | {{ $m->release_year }} | {{ $m->duration }} мин
                            </p>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($m->description, 100) }}
                            </p>
                            <div class="mt-2 mb-3">
                                <strong class="small">Жанры:</strong><br>
                                @forelse($m->genres as $g)
                                    <span class="badge bg-secondary me-1">{{ $g->name }}</span>
                                @empty
                                    <span class="text-muted small">Не указаны</span>
                                @endforelse
                            </div>
                            <a href="{{ route('movies.show', $m->slug) }}" class="btn btn-outline-primary w-100 mt-auto">Подробнее</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">По вашему запросу фильмы не найдены.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection