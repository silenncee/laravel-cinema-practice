<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Movies;
use App\Models\Halls;
use App\Models\Tickets;
use Carbon\Carbon; 
use Illuminate\Support\Collection;

class Screenings extends Model
{
    protected $fillable = ['movie_id', 'hall_id', 'start_time', 'format', 'ticket_price'];

    public function movie() { return $this->belongsTo(Movies::class, 'movie_id'); }
    public function hall() { return $this->belongsTo(Halls::class); }
    public function tickets() { return $this->hasMany(Tickets::class); }


    public function bookedSeats(): Collection
    {
        return $this->tickets()->where('status', 'paid')->pluck('row', 'seat');
    }


    public function overlapsWith(?Carbon $start, ?Carbon $end): bool
    {

        $existing = self::where('hall_id', $this->hall_id)
            ->where('id', '!=', $this->id)
            ->get();

        foreach ($existing as $screening) {
            if ($screening->start_time && $screening->endTime()) {
                if ($screening->start_time->lt($end) && $screening->endTime()->gt($start)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function endTime(): Carbon
    {
        return $this->start_time->addMinutes($this->movie->duration);
    }
}
