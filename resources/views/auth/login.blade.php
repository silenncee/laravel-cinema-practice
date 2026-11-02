@extends('layout.index')

@section('title', 'Войти')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <h2>Войти</h2>
        <form method="post" action="{{ route('login') }}">
            @csrf
            <input type="email" name="email" placeholder="Введите email" required><br>
            <input type="password" name="password" placeholder="Введите пароль" required><br><br>
            <input type="submit" value="Войти">
        </form>
    </div>
</div>
@endsection