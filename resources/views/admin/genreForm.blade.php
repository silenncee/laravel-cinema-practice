@extends('admin.panel')

@section('admin-content')
    <h2>{{ $genre ? 'Редактирование жанра' : 'Добавление нового жанра' }}</h2>

    <form action="{{ $genre ? route('admin.genres.form', $genre) : route('admin.genres.form') }}" method="post">
        @csrf
        @if($genre)
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Название жанра *</label>
            <input type="text" name="name" class="form-control" 
                   value="{{ old('name', $genre->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Slug (URL)</label>
            <input type="text" name="slug" class="form-control" 
                   value="{{ old('slug', $genre->slug ?? '') }}">
            <div class="form-text">
                
            </div>
        </div>

        <button type="submit" class="btn btn-success">
            {{ $genre ? 'Сохранить изменения' : 'Создать жанр' }}
        </button>
        <a href="{{ route('admin.genres') }}" class="btn btn-secondary ms-2">Отмена</a>
    </form>
@endsection