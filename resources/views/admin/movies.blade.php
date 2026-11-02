@extends('admin.panel')

@section('admin-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Фильмы</h2>
        <a href="{{ route('admin.movies.form') }}" class="btn btn-primary">+ Добавить фильм</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Постер</th>
                <th>Название</th>
                <th>Жанры</th>
                <th>Год</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($movies as $m)
                <tr>
                    <td>{{ $m->id }}</td>
                    <td>
                        @if($m->poster_url)
                            <img src="{{ asset($m->poster_url) }}" alt="Poster" style="height: 50px; object-fit: cover;">
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $m->title }}</td>
                    <td>{{ $m->genres->pluck('name')->join(', ') ?: '—' }}</td>
                    <td>{{ $m->release_year }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.movies.form', $m) }}" class="btn btn-sm btn-warning">Ред.</a>
                        @if(!$m->screenings()->exists())
                            <form action="{{ route('admin.movies.delete', $m) }}" method="post" class="d-inline" onsubmit="return confirm('Удалить фильм?')">
                                @csrf 
                                <button type="submit" class="btn btn-sm btn-danger">Удал.</button>
                            </form>
                        @else
                            <button class="btn btn-sm btn-secondary" disabled>Занят</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4">Фильмы не найдены</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $movies->links() }}
@endsection