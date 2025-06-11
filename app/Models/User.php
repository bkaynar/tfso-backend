<?php
namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // Bu satırı ekleyin
use Filament\Panel; // Bu satırı ekleyin
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

/*
    * @OA\Schema(
    *     schema="User",
    *     title="User",
    *     description="Model representing a user in the system",
    *     @OA\Property(property="id", type="integer", format="int64", description="Unique identifier for the user", readOnly=true),
    *     @OA\Property(property="name", type="string", description="Name of the user"),
    *     @OA\Property(property="email", type="string", format="email", description="Email address of the user"),
    *     @OA\Property(property="password", type="string", format="password", description="Password for the user account"),
    *     @OA\Property(property="profile_photo", type="string", description="URL of the user's profile photo"),
    *     @OA\Property(property="bio", type="string", description="Short biography of the user"),
    *     @OA\Property(property="instagram", type="string", description="Instagram handle of the user"),
    *     @OA\Property(property="twitter", type="string", description="Twitter handle of the user"),
    *     @OA\Property(property="facebook", type="string", description="Facebook profile URL of the user"),
    *     @OA\Property(property="tiktok", type="string", description="TikTok handle of the user"),
    *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp of the user account", readOnly=true),
    *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp of the user account", readOnly=true)
    * )
    */
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasTranslations;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'bio',
        'instagram',
        'twitter',
        'facebook',
        'tiktok',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // İlişkiler
    public function sets()
    {
        return $this->hasMany(Set::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function favoriteSets()
    {
        return $this->belongsToMany(Set::class, 'favorite_sets')->withTimestamps();
    }

    public function favoriteTracks()
    {
        return $this->belongsToMany(Track::class, 'favorite_tracks')->withTimestamps();
    }

    public function favoriteRadios()
    {
        return $this->belongsToMany(Radio::class, 'favorite_radios')->withTimestamps();
    }

    public function favoriteDJs()
    {
        return $this->belongsToMany(User::class, 'favorite_djs', 'user_id', 'favorited_user_id')->withTimestamps();
    }


    public function canAccessPanel(Panel $panel): bool
    {
        // Spatie Roles kullanarak "admin" rolüne sahip kullanıcıların erişimini sağlayın.
        // Bu, en güvenli ve önerilen yaklaşımdır.
        return $this->hasRole('admin' || 'dj');

        // Eğer birden fazla role sahip kullanıcıların erişmesini istiyorsanız:
        // return $this->hasAnyRole(['admin', 'editor']);

        // Veya sadece belirli bir e-posta alan adına sahip kullanıcıların erişmesini istiyorsanız (daha az güvenli):
        // return str_ends_with($this->email, '@yourdomain.com');

        // DİKKAT: Aşağıdaki satırı üretim ortamında KULLANMAYIN!
        // return true; // Bu, herkesin paneli görmesine izin verir ve güvenlik açığıdır.
    }
}
