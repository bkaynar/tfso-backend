<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Set extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'cover_image',
        'audio_file', // 🎧 dosya yolu
        'is_premium',
        'iap_product_id',
    ];

    public array $translatable = ['name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
