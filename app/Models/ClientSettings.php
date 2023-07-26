<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSettings extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'client_settings';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'video_profile_settings',
        'audio_profile_setting',
        'status'
    ];

}
