@extends('admin.panel')
@section('admin-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Кинозалы</h2>
        <a href="{{ route('admin.halls.form') }}" class="btn btn-primary">+ Добавить зал</a>
    </div>

    <table class="table">
        <thead><tr><th>ID</th><th>Название</th><th>Схема</th><th></th></tr></thead>
        <tbody>
        @forelse($halls as $h)
            <tr>
                <td>{{ $h->id }}</td>
                <td>{{ $h->name }}</td>
                <td>{{ $h->rows_count }}x{{ $h->seats_per_row }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.halls.form', $h) }}" class="btn btn-sm btn-warning">Ред.</a>
                    @if(!$h->screenings()->exists())
                        <form action="{{ route('admin.halls.delete', $h) }}" method="post" class="d-inline" onsubmit="return confirm('Удалить?')">
                            @csrf <button class="btn btn-sm btn-danger">Удал.</button>
                        </form>
                    @else
                        <button class="btn btn-sm btn-secondary" disabled>Занят</button>
                    @endif
                </td>
            </tr>
        @empty <tr><td colspan="4" class="text-center">Залы не найдены</td></tr> @endforelse
        </tbody>
    </table>
    {{ $halls->links() }}
@endsection