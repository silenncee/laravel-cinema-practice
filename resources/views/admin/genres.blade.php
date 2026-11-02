@extends('admin.panel')

@section('admin-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Жанры</h2>
        <a href="{{ route('admin.genres.form') }}" class="btn btn-primary">
            <i class="bi-plus-lg"></i> Добавить жанр
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Slug</th>
                <th class="text-end">Действия</th>
            </tr>
            </thead>
            <tbody>
            @forelse($genres as $genre)
                <tr>
                    <td>{{ $genre->id }}</td>
                    <td>{{ $genre->name }}</td>
                    <td>{{ $genre->slug }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.genres.form', $genre) }}" class="btn btn-sm btn-warning me-2">
                            <i class="bi-pencil"></i> Ред.
                        </a>
                        <form action="{{ route('admin.genres.delete', $genre) }}" method="post" class="d-inline"
                              onsubmit="return confirm('Удалить жанр?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi-trash"></i> Удал.
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4">Жанры не найдены</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $genres->links() }}
@endsection