@extends('layout.index')

@section('title', 'Мои билеты')
@section('content')
<div class="container">
    <h2>Мои билеты</h2>
    @if($active->isEmpty())
        <div class="alert alert-info">Пока нет купленных билетов.</div>
    @else
        @foreach($active as $ticket)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $ticket->screening->movie->title }}</h5>
                    <p>
                        {{ $ticket->screening->start_time->format('d.m.Y H:i') }}
                        — Зал {{ $ticket->screening->hall->name }}
                        — Место {{ $ticket->row }}-{{ $ticket->seat }}
                    </p>
                    <a href="{{ route('user.ticket', $ticket) }}" class="btn btn-sm btn-outline-primary">
                        Посмотреть QR
                    </a>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection