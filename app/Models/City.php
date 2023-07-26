<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\State;

class City extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'state_id',
        'latitude',
        'longitude',
        'status'
    ];

    // Relationship with the State model
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    // Relationship with the Country model through the State model
    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id')->through(State::class);
    }
}
