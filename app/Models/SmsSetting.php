<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSetting extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'title',
        'template',
        'variables',
        'status',
        'user_id'
    ];
}