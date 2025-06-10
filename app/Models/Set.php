<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @OA\Schema(
 *     schema="Set",
 *     title="Set",
 *     description="Model representing a set of tracks",
 *     @OA\Property(property="id", type="integer", format="int64", description="Unique identifier for the set", readOnly=true),
 *     @OA\Property(property="user_id", type="integer", format="int64", description="ID of the user who created the set"),
 *     @OA\Property(property="name", type="string", description="Name of the set"),
 *     @OA\Property(property="description", type="string", description="Description of the set"),
 *     @OA\Property(property="cover_image", type="string", description="URL of the cover image"),
 *     @OA\Property(property="audio_file", type="string", description="Path to the audio file"),
 *     @OA\Property(property="is_premium", type="boolean", description="Indicates if the set is premium"),
 *     @OA\Property(property="iap_product_id", type="string", description="In-app purchase product ID for premium access"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp of the set", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp of the set", readOnly=true)
 * )
 */
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
        'category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
