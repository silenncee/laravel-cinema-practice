@extends('layout.index')

@section('title', 'Регистрация')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <h2>Регистрация</h2>
        <form method="post" action="{{ route('register.store') }}">
            @csrf
            <input type="text" name="name" placeholder="Введите имя" required><br>
            <input type="email" name="email" placeholder="Введите email" required><br>
            <input type="password" name="password" placeholder="Введите пароль" required><br>
            <input type="password" name="password_confirmation" placeholder="Тот же пароль" required><br><br>
            <input type="submit" value="Зарегистрироваться">
        </form>
    </div>
</div>
@endsection