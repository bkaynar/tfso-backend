<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastPlayedContent extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'last_played_set_id', 'last_played_track_id', 'last_played_radio_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
