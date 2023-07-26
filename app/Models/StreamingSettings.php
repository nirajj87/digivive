<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamingSettings extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'slug',
        'additional_settings',
        'status'
    ];
}
