<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwsImageSetting extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'storage_type',
        'copy_credential_same_as_aws',
        'key',
        'secret',
        'region',
        'default_acl',
        'bucket',
        'row',
        'content',
        'backup',
        'user_id',
        'status',
        'cdn_url',
    ];
}
