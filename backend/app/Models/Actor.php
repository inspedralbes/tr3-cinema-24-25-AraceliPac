<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Actor extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'rating', 'favorite_movie_id'];

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }
}
