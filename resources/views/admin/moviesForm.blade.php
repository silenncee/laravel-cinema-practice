@extends('admin.panel')

@section('admin-content')
    <h2 style="font-family: sans-serif; color: #333;">
        {{ $movie ? 'Редактирование фильма' : 'Добавление фильма' }}
    </h2>

    {{-- ошиьки) --}}
    @if ($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 3px; margin-bottom: 15px; font-family: sans-serif;">
            <strong>Ошибка при сохранении:</strong>
            <ul style="margin: 5px 0 0 15px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $movie ? route('admin.movies.form', $movie) : route('admin.movies.form') }}" method="post" enctype="multipart/form-data" style="font-family: sans-serif; font-size: 14px;">
        @csrf
        @if($movie) @method('PUT') @endif

        {{-- Левая колонка: Основные данные --}}
        <div style="margin-bottom: 20px;">
            <div style="margin-bottom: 10px;">
                <label style="display: block; font-weight: bold; margin-bottom: 4px;">Название *</label>
                <input type="text" name="title" value="{{ old('title', $movie->title ?? '') }}" required style="width: 100%; max-width: 500px; padding: 6px; border: 1px solid #ccc; border-radius: 3px;">
            </div>

            <div style="margin-bottom: 10px;">
                <label style="display: block; font-weight: bold; margin-bottom: 4px;">Slug (URL)</label>
                <input type="text" name="slug" value="{{ old('slug', $movie->slug ?? '') }}" style="width: 100%; max-width: 500px; padding: 6px; border: 1px solid #ccc; border-radius: 3px;">
                <small style="color: #666;"></small>
            </div>

            <div style="margin-bottom: 10px;">
                <label style="display: block; font-weight: bold; margin-bottom: 4px;">Описание</label>
                <textarea name="description" rows="4" style="width: 100%; max-width: 500px; padding: 6px; border: 1px solid #ccc; border-radius: 3px;">{{ old('description', $movie->description ?? '') }}</textarea>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 10px;">
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 4px;">Длительность (мин) *</label>
                    <input type="number" name="duration" value="{{ old('duration', $movie->duration ?? 90) }}" required min="1" style="width: 120px; padding: 6px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 4px;">Год *</label>
                    <input type="number" name="release_year" value="{{ old('release_year', $movie->release_year ?? date('Y')) }}" required min="1900" style="width: 100px; padding: 6px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 4px;">Возраст *</label>
                    <select name="age_rating" required style="padding: 6px; border: 1px solid #ccc; border-radius: 3px;">
                        @foreach(['0+', '6+', '12+', '16+', '18+'] as $r)
                            <option value="{{ $r }}" {{ (old('age_rating', $movie->age_rating ?? '') == $r) ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 10px;">
                <label style="display: block; font-weight: bold; margin-bottom: 4px;">Жанры</label>
                <select name="genres[]" multiple size="6" style="width: 100%; max-width: 300px; padding: 6px; border: 1px solid #ccc; border-radius: 3px;">
                    @foreach($allGenres as $g)
                        <option value="{{ $g->id }}" {{ (is_array(old('genres')) && in_array($g->id, old('genres'))) || ($movie && $movie->genres->contains($g->id)) ? 'selected' : '' }}>{{ $g->name }}</option>
                    @endforeach
                </select>
                <br><small style="color: #666;">Ctrl, чтобы выбрать несколько</small>
            </div>
        </div>

        {{-- Правая колонка: Медиа --}}
        <div style="border-top: 1px solid #eee; padding-top: 15px; margin-bottom: 20px;">
            <div style="margin-bottom: 10px;">
                <label style="display: block; font-weight: bold; margin-bottom: 4px;">Постер</label>
                <input type="file" name="poster" accept="image/*" style="border: 1px solid #ccc; padding: 4px; border-radius: 3px; background: #fafafa;">
                @if($movie?->poster_url)
                    <div style="margin-top: 5px;">
                        <img src="{{ asset($movie->poster_url) }}" style="max-height: 150px; border: 1px solid #ddd; padding: 2px;">
                    </div>
                @endif
            </div>

            <div style="margin-bottom: 10px;">
                <label style="display: block; font-weight: bold; margin-bottom: 4px;">Трейлер (Файл)</label>
                <input type="file" name="trailer" accept="video/*" style="border: 1px solid #ccc; padding: 4px; border-radius: 3px; background: #fafafa;">
            </div>

            <div style="margin-bottom: 10px;">
                <label style="display: block; font-weight: bold; margin-bottom: 4px;">Трейлер (Ссылка)</label>
                <input type="url" name="trailer_url_input" placeholder="https://" value="{{ old('trailer_url_input', $movie->trailer_url ?? '') }}" style="width: 100%; max-width: 400px; padding: 6px; border: 1px solid #ccc; border-radius: 3px;">
            </div>

            <div style="margin-bottom: 10px;">
                <label style="display: block; font-weight: bold; margin-bottom: 4px;">Кадры из фильма (Галерея)</label>
                <input type="file" name="frames[]" accept="image/*" multiple style="border: 1px solid #ccc; padding: 4px; border-radius: 3px; background: #fafafa;">
                <br><small style="color: #666;">Можно выбрать несколько файлов</small>
            </div>

            @if($movie && $movie->images()->where('type', 'frame')->count() > 0)
                <div style="display: flex; flex-wrap: wrap; gap: 5px; margin-top: 5px;">
                    @foreach($movie->images()->where('type', 'frame')->orderBy('sort_order')->get() as $img)
                        <img src="{{ asset($img->image_url) }}" style="height: 60px; border: 1px solid #ddd; padding: 2px;">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Кнопки --}}
        <div style="border-top: 1px solid #eee; padding-top: 15px;">
            <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 8px 16px; border-radius: 3px; cursor: pointer; font-size: 14px;">
                {{ $movie ? 'Сохранить' : 'Создать' }}
            </button>
            <a href="{{ route('admin.movies') }}" style="display: inline-block; background-color: #dc3545; color: white; text-decoration: none; padding: 8px 16px; border-radius: 3px; margin-left: 10px; font-size: 14px;">
                Отмена
            </a>
        </div>
    </form>
@endsection