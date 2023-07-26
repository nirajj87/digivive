<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranscodingSettings extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'video_presets_id',
        'video_download_type_id',
        'audio_presets_id',
        'audio_download_type_id',
        'status'
    ];
}
