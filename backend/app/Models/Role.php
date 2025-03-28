<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    protected $fillable = ['name'];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
