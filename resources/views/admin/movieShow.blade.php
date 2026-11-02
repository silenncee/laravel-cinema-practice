@extends('layout.index')

@section('title', $movie->title)
@section('description', Str::limit($movie->description, 150))
@section('keywords', $movie->genres->pluck('name')->join(', '))
@if($movie->poster_url)
    @section('og_image', asset($movie->poster_url))
@endif

@section('content')
<div class="container my-4">
    <div class="row">
     
        <div class="col-md-4 text-center">
            @if($movie->poster_url)
                <img src="{{ asset($movie->poster_url) }}" class="img-fluid rounded shadow" alt="{{ $movie->title }}">
            @else
                <div class="bg-secondary text-white text-center py-5 rounded">Нет постера</div>
            @endif
        </div>

        <div class="col-md-8">
            <h1>{{ $movie->title }}</h1>
            <p class="lead">{{ $movie->description }}</p>

            <div class="row text-muted small">
                <div class="col-auto"><strong>Год:</strong> {{ $movie->release_year }}</div>
                <div class="col-auto"><strong>Длительность:</strong> {{ $movie->duration }} мин</div>
                <div class="col-auto"><strong>Возраст:</strong> {{ $movie->age_rating }}</div>
            </div>

            <div class="mt-3">
                <strong>Жанры:</strong>
                @forelse($movie->genres as $g)
                    <span class="badge bg-info me-1">{{ $g->name }}</span>
                @empty
                    <span class="text-muted">Не указаны</span>
                @endforelse
            </div>

          
            @if($movie->trailer_url)
                <div class="mt-4">
                    <h5>Трейлер</h5>
                    @if(str_contains($movie->trailer_url, 'youtube.com') || str_contains($movie->trailer_url, 'youtu.be'))
                        @php
                            $videoId = preg_match('/(?:v=|\/)([0-9A-Za-z_-]{11}).*/', $movie->trailer_url, $matches) ? $matches[1] : '';
                        @endphp
                        @if($videoId)
                            <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                                <iframe src="{{ $videoId }}" style="position:absolute; top:0; left:0; width:100%; height:100%;" frameborder="0" allowfullscreen></iframe>
                            </div>
                        @endif
                    @elseif(str_contains($movie->trailer_url, 'storage/trailers/'))
                        <video controls class="w-100" style="max-height: 400px;">
                            <source src="{{ asset($movie->trailer_url) }}" type="video/mp4">
                            Ваш браузер не поддерживает видео.
                        </video>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Галерея  --}}
    @if($movie->images()->where('type', 'frame')->count() > 0)
        <hr class="my-5">
        <h3>Галерея кадров</h3>
        <div class="row">
            @foreach($movie->images()->where('type', 'frame')->orderBy('sort_order')->get() as $img)
                <div class="col-md-3 col-sm-4 col-6 mb-3">
                    <img src="{{ asset($img->image_url) }}" class="img-fluid rounded" alt="Кадр">
                </div>
            @endforeach
        </div>
    @endif

    <hr class="my-5">

 
    <h2>Расписание сеансов</h2>
    
    @if(isset($screenings) && $screenings->count() > 0)
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Дата и время</th>
                    <th>Зал</th>
                    <th>Формат</th>
                    <th>Цена</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                @foreach($screenings as $s)
                    <tr>
                        <td>{{ $s->start_time->format('d.m.Y H:i') }}</td>
                        <td>{{ $s->hall->name ?? '—' }}</td>
                        <td>{{ $s->format ?? '2D' }}</td>
                        <td>{{ $s->price }} ₽</td>
                        <td>
                            @if($s->start_time->isPast())
                                <span class="text-danger">Сеанс прошёл</span>
                            @else
                                {{-- ссылка на покупку заглушка --}}
                                <a href="#" class="btn btn-sm btn-primary">Купить билет</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-warning mt-3">На этот фильм пока нет сеансов.</div>
    @endif

</div>
@endsection