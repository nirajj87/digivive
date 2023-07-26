<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwsStorageSetting extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'storage_type',
        'key',
        'secret',
        'region',
        'default_acl',
        'bucket',
        'row_folder',
        'content_folder',
        'backup_folder',
        'user_id',
        'cdn_url',
        'is_selected',
        'status'
    ];

}
