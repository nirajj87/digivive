<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudinaryImageSettings extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'cloudinary_image_settings';
    public $incrementing = true;

    protected $fillable = [
        'storage_type',
        'key',
        'secret',
        'bucket',
        'status',
        'cdn_url',
        'is_selected',
        'user_id',
    ];
}