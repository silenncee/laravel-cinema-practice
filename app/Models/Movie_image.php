<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie_image extends Model
{
    protected $fillable = ['movie_id', 'image_url', 'type', 'sort_order']; 


    public function movie() 
    { 
        return $this->belongsTo(Movies::class, 'movie_id'); 
    }
}