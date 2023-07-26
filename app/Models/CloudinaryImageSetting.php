<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudinaryImageSetting extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'storage_type',
        'key',
        'secret',
        'bucket',
        'user_id',
        'status',
        'cdn_url',
    ];
}
