<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;
use App\Models\Countries;

class State extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'state_id';
    protected $fillable = [
        'title',
        'state_code',
        'state_id',
        'country_id',
        'latitude',
        'longitude',
        'status'
    ];

    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id')->select('id','title');
    }
   
    public function city()
    {
        return $this->hasMany(City::class, 'state_id')->select('id', 'title');
    }

}
