<?php
// app/Models/AccessLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * schema="AccessLog",
 * title="AccessLog",
 * description="Erişim kayıtları modeli",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="Erişim kaydının benzersiz ID'si",
 * readOnly=true
 * ),
 * @OA\Property(
 * property="user_id",
 * type="integer",
 * format="int64",
 * description="Erişimi yapan kullanıcının ID'si"
 * ),
 * @OA\Property(
 * property="content_type",
 * type="string",
 * description="Erişilen içeriğin türü (örn: 'Post', 'Comment')",
 * maxLength=255
 * ),
 * @OA\Property(
 * property="content_id",
 * type="integer",
 * format="int64",
 * description="Erişilen içeriğin ID'si"
 * ),
 * @OA\Property(
 * property="accessed_at",
 * type="string",
 * format="date-time",
 * description="Erişimin gerçekleştiği zaman damgası (ISO 8601 formatında)",
 * example="2023-10-27T10:00:00Z"
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * format="date-time",
 * description="Kaydın oluşturulma zamanı",
 * readOnly=true
 * ),
 * @OA\Property(
 * property="updated_at",
 * type="string",
 * format="date-time",
 * description="Kaydın son güncellenme zamanı",
 * readOnly=true
 * )
 * )
 */
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
