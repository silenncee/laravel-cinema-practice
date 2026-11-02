@extends('admin.panel')
@section('admin-content')
    <h2>{{ $hall ? 'Редактирование зала' : 'Добавление зала' }}</h2>
    <form action="{{ $hall ? route('admin.halls.form', $hall) : route('admin.halls.form') }}" method="post">
        @csrf
        @if($hall) @method('PUT') @endif
        <div class="row">
            <div class="col-md-6"><div class="mb-3"><label>Название *</label><input type="text" name="name" class="form-control" value="{{ old('name', $hall->name ?? '') }}" required></div></div>
            <div class="col-md-3"><div class="mb-3"><label>Ряды *</label><input type="number" name="rows_count" class="form-control" min="1" max="50" value="{{ old('rows_count', $hall->rows_count ?? 6) }}" required></div></div>
            <div class="col-md-3"><div class="mb-3"><label>Мест в ряду *</label><input type="number" name="seats_per_row" class="form-control" min="1" max="50" value="{{ old('seats_per_row', $hall->seats_per_row ?? 10) }}" required></div></div>
        </div>
        <button type="submit" class="btn btn-success">{{ $hall ? 'Сохранить' : 'Создать' }}</button>
        <a href="{{ route('admin.halls') }}" class="btn btn-secondary ms-2">Отмена</a>
    </form>
@endsection