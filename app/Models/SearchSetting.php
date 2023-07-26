<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchSetting extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'search_type',
        'app_id',
        'key',
        'collection_name',
        'host',
        'port',
        'protocol',
        'user_id',
        'status',
    ];

     
}
