<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DateFormats extends BaseModel
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'date_formats';
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
     protected $fillable = [
        'title',
        'format_key',
        'flag',
        'order',
        'status',
    ];
}
