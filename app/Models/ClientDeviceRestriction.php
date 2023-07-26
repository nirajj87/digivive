<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDeviceRestriction extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'client_device_restrictions';
    public $incrementing = true;

    protected $fillable = [
        'number_of_device',
        'os_id',
        'api_duration',
        'status',
        'user_id'
    ];
}
