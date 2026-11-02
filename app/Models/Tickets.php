<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Screenings;

class Tickets extends Model
{
    protected $fillable = [
        'user_id','screening_id','row','seat','status','qr_code','email_sent'
    ];
    public function user() { return $this->belongsTo(User::class); }
    public function screening() { return $this->belongsTo(Screenings::class); }
}
