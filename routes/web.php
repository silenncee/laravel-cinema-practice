<?php

use Illuminate\Support\Facades\Route;
use App\Models\Genres;
use App\Models\Halls;
use App\Models\Movies;
use App\Models\Screenings; // Обязательно добавьте эту строку!
use App\Http\Controllers\MovieController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

// ==========================================
// ПУБЛИЧНАЯ ЧАСТЬ
// ==========================================

// Главная страница (с поиском и фильтром по жанрам)
Route::get('/', function (\Illuminate\Http\Request $request) {
    $movies = Movies::with('genres')
        ->when($request->q, fn($q) => $q->where('title', 'like', "%{$request->q}%"))
        ->when($request->genre, fn($q) => $q->whereHas('genres', fn($g) => $g->where('slug', $request->genre)))
        ->orderBy('title')
        ->get(); 

    $genres = Genres::orderBy('name')->get();

    return view('welcome', compact('movies', 'genres'));
})->name('home');

// Страница конкретного фильма (с передачей сеансов)
Route::get('/movies/{slug}', function ($slug) { 
    $movie = Movies::with('genres')->where('slug', $slug)->firstOrFail(); 
    
    // Получаем сеансы для этого фильма
    $screenings = Screenings::where('movie_id', $movie->id)
                            ->with('hall')
                            ->orderBy('start_time')
                            ->get();

    return view('admin.movieShow', compact('movie', 'screenings')); 
})->name('movies.show');

// ==========================================
// АУТЕНТИФИКАЦИЯ
// ==========================================
Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); 
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// ЛИЧНЫЙ КАБИНЕТ
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/tickets/{ticket}', [UserController::class, 'showTicket'])->name('user.ticket');
});

// ==========================================
// АДМИН-ПАНЕЛЬ
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/panel', [AdminController::class, 'panel'])->name('panel');

    // Жанры
    Route::get('/admin/genres', [GenreController::class, 'index'])->name('admin.genres');
    Route::match(['get', 'post', 'put'], '/admin/genres/form/{genre?}', [GenreController::class, 'form'])->name('admin.genres.form');
    Route::post('/admin/genres/{genre}/delete', [GenreController::class, 'destroy'])->name('admin.genres.delete');

    // Залы
    Route::get('/admin/halls', [HallController::class, 'index'])->name('admin.halls');
    Route::match(['get', 'post', 'put'], '/admin/halls/form/{hall?}', [HallController::class, 'form'])->name('admin.halls.form');
    Route::post('/admin/halls/{hall}/delete', [HallController::class, 'destroy'])->name('admin.halls.delete');

    // Фильмы
    Route::get('/admin/movies', [MovieController::class, 'index'])->name('admin.movies');
 Route::match(['get', 'post', 'put'], '/admin/movies/form/{movie?}', [MovieController::class, 'form'])->name('admin.movies.form');
    Route::post('/admin/movies/{movie}/delete', [MovieController::class, 'destroy'])->name('admin.movies.delete');
});