<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $fillable = ['name', 'lastname', 'birth_date', 'nationality', 'bio'];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
