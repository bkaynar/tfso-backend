<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Radio extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'image',
        'stream_url',
        'is_premium',
    ];

    public array $translatable = ['name'];
}
