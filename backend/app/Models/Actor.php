<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Actor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'birth_date',
        'nationality',
        'bio',
        'image'
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_actor', 'actor_id', 'movie_id');
    }
}
