<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Кинотеатр' }} — Синема</title>
    <style>
        /* Минимальный сброс и базовые стили */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #222;
            color: white;
            padding: 10px 20px;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1000px;
            margin: 0 auto;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .user-menu {
            font-size: 14px;
        }
        main {
            flex: 1;
            max-width: 1000px;
            width: 100%;
            margin: 20px auto;
            padding: 0 15px;
            box-sizing: border-box;
        }
        footer {
            background-color: #222;
            color: #aaa;
            text-align: center;
            padding: 10px;
            font-size: 13px;
            margin-top: auto;
        }
        /* Стили для уведомлений */
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert ul { margin: 0; padding-left: 20px; }
    </style>
</head>
<body>
    <header>
        <nav>
            <div>
                <a href="{{ route('home') }}"><strong>Синема</strong></a>
                <a href="{{ route('home') }}">Афиша</a>
                @auth
                    <a href="{{ route('profile') }}">Мои билеты</a>
                @endauth
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('panel') }}">Админка</a>
                @endif
            </div>

            <div class="user-menu">
                @guest
                    <a href="{{ route('login') }}">Войти</a>
                    <a href="{{ route('register') }}">Регистрация</a>
                @endguest

                @auth
                    <span style="margin-right: 10px;">{{ auth()->user()->email }}</span>
                    <form action="{{ route('logout') }}" method="post" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: white; cursor: pointer; text-decoration: underline; font-size: 14px;">Выйти</button>
                    </form>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        &copy; 2026 Синема. Все права защищены.
    </footer>
</body>
</html>