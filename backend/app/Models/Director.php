<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $fillable = ['first_name', 'last_name', 'rating', 'favorite_movie_id'];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
