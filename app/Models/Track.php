<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Track extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'audio_file',
        'duration',
        'is_premium',
        'iap_product_id',
    ];

    public array $translatable = ['name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
