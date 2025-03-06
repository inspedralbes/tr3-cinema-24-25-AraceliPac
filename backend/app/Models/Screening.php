<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    protected $fillable = ['movie_id', 'room_id', 'start_time', 'end_time'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
