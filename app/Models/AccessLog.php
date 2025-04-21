<?php
// app/Models/AccessLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content_type',
        'content_id',
        'accessed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
