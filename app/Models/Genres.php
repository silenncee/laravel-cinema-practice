<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Movies;

class Genres extends Model
{
    protected $fillable = ['slug', 'name'];

    public function movies() 
    { 
        return $this->belongsToMany(Movies::class, 'movie_genre', 'genre_id', 'movie_id');
    }
}