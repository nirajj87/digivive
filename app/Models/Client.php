<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mail;

class Client extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'country_id',
        'timezone',
        'date_format',
        'password',
        'profile_img',
        'company_name',
        'role_id',
        'status',
        'permission',
        'dob',
        'phone_number',
        'is_parent',
        'created_by',
        'updated_by',

    ];

    protected static function booted()
    {
        static::addGlobalScope(function ($query) {
            $query->clients();
        });
    }

    public function scopeClients($query)
    {
        return $query->where('role_id', 2);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function clientTimezone()
    {
        return $this->belongsTo(Timezones::class, 'timezone')->select('id', 'title', 'timezone', 'zone_name');
    }

    public function clientDate_format()
    {
        return $this->belongsTo(DateFormats::class, 'date_format')->select('id','title','format_key');
    }
    
    public function sendRandomPasswordNotification($randomPassword)
    {   
        $viewData = [
            'password' => $randomPassword,
        ];
        $emailBody = view('email.welcomeUser', $viewData)->render();
        Mail::send('email.welcomeUser', $viewData, function ($message){
            $message->to($this->email)  
                ->subject('Your new password');
        });
    }

   public function user_profile()
{
    return $this->hasMany(UserProfile::class, 'user_id');
}
}

