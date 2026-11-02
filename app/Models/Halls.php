<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Screenings;

class Halls extends Model
{
    protected $fillable = ['name','rows_count','seats_per_row'];
    public function screenings() { return $this->hasMany(Screenings::class, 'hall_id'); }
}