<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsManagement extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'slug',
        'tracking_id',
        'additional_settings',
        'status'
    ];
}
