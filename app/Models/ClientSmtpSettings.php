<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSmtpSettings extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'client_smtp_settings';
    public $incrementing = true;

    protected $fillable = [
        'host',
        'user_name',
        'password',
        'port',
        'encryption',
        'from_email',
        'user_id',
        'status',
    ];
}
