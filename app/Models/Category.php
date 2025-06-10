<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
    ];

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function sets()
    {
        return $this->hasMany(Set::class);
    }
}
