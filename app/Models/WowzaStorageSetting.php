<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WowzaStorageSetting extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'storage_type',
        'host',
        'user_name',
        'password',
        'content_path',
        'wowza_path_format',
        'user_id',
        'cdn_url',
        'is_selected',
        'status',
        
    ];
}
