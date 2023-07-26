<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwsImageSettings extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'aws_image_settings';
    public $incrementing = true;

    protected $fillable = [
        'storage_type',
        'copy_credential_same_as_aws',
        'key',
        'secret',
        'region',
        'default_acl',
        'bucket',
        'row_folder',
        'content_folder',
        'backup_folder',
        'path_format',
        'cdn_url',
        'is_selected',
        'status',
        'user_id'
    ];
}