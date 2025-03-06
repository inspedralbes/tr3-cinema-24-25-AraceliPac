<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'release_year', 'rating', 'duration','image', 'trailer', 'genre_id', 'director_id'];

    public function director()
    {
        return $this->belongsTo(Director::class);
    }
    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }
    //relació muchos a muchos 
    public function actors()
    {
        return $this->belongsToMany(Actor::class);
    }
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
