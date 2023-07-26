<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitframe extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'type',
        'presets',
        'is_downloadable',
        'download_type',
        'status'
    ];
}
