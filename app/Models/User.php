<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable
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

    public $translatable = ['name', 'bio'];

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

}
