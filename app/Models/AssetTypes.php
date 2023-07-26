<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTypes extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'status'
    ];
}
