<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends BaseModel
{
    use HasFactory;
    protected $table = 'user_profile';

    protected $fillable = [
        'user_id',
        'user_name',
        'designation',
        'department',
        'company_name',
        'company_logo',
        'address',
        'communication_channel',
        'billing',
        'managers',
        'landmark',
        'country_id',
        'state_id',
        'city_id',
        'zip_code',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function managers()
    // {  
    //     return $this->belongsTo(User::class)->whereIn('id', json_decode($this->manager_ids));
    // }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id')->select('id', 'title');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id')->select('id','state_id', 'title');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id')->select('id', 'title');
    }
}
