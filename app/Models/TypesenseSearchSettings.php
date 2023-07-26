<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypesenseSearchSettings extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'app_id',
        'key',
        'collection_name',
        'host',
        'port',
        'protocol',
        'status',
        'is_selected',
        'user_id'
    ];
}
