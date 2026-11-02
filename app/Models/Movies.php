<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'duration',
        'release_year',
        'age_rating',
        'poster_url',
        'trailer_url',
    ];

    // Связь с жанрами
    public function genres()
    {
        return $this->belongsToMany(Genres::class, 'movie_genre', 'movie_id', 'genre_id');
    }

  

    public function images()
    {
 //картинки
        return $this->hasMany(Movie_image::class, 'movie_id');
    }
    // с сеансами
    public function screenings()
    {
        return $this->hasMany(Screenings::class, 'movie_id');
    }
}

