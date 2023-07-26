<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Countries extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'countries';
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
     protected $fillable = [
        'title',
        'iso3',
        'iso2',
        'phone_code',
        'capital',
        'currency',
        'currency_name',
        'currency_symbol',
        'timezones',
        'latitude',
        'longitude',
        'emoji',
        'image',
        'status',
    ];

    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }
}
