<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Messages\MailMessage;
use Mail;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements JWTSubject,Auditable
{
    use HasFactory, Notifiable;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'profile_img',
        'company_name',
        'role_id',
        'permission',
        'country_id',
        'timezone',
        'date_format',
        'password_last_updated',
        'dob',
        'phone_number',
        'status',
        'is_two_factor_enable',
        'is_parent',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // protected static function booted()
    // {
    //     static::addGlobalScope(function ($query) {
    //         $query->admin();
    //     });
    // }

    public function scopeAdmin($query)
    {
        return $query->where('role_id', 1);
    }

    public function scopeClient($query)
    {
        return $query->where('role_id', 2);
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

    public function sendTwoFactorOtpNotification($otp)
    {
        $viewData = [
            'otp' => $otp,
        ];
        $emailBody = view('email.twoFactorAuth', $viewData)->render();
        Mail::send('email.twoFactorAuth', $viewData, function ($message) {
            $message->to($this->email)
                ->subject('Your Two-Factor Authentication Code');
        });
    }

      public function resendTwoFactorOtpNotification($otp)
    {
        $viewData = [
            'otp' => $otp,
        ];
        $emailBody = view('email.resendTwoFactorOtpNotification', $viewData)->render();
        Mail::send('email.resendTwoFactorOtpNotification', $viewData, function ($message) {
            $message->to($this->email)
                ->subject('Your Two-Factor Authentication Resend Code');
        });
    }
    public function beforEnableDisableTwoFactorOtpNotification($otp, $activeStatus)
    {
        $viewData = [
            'otp' => $otp,
            'activeStatus' => $activeStatus,
        ];

        $subjectText = ($activeStatus == 1) ? 'Your Two-Factor Authentication Deactivation Code' : 'Your Two-Factor Authentication Activation Code';

        $emailBody = view('email.beforEnableDisableTwoFactorOtpNotification', $viewData)->render();

        Mail::send('email.beforEnableDisableTwoFactorOtpNotification', $viewData, function ($message) use ($subjectText) {
            $message->to($this->email)
                ->subject($subjectText);
        });
    }
    

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id')->select('id','title');
    }

    // public function timezone()
    // {
    //     return $this->belongsTo(Timezones::class, 'timezone')->select('id','title','timezone','zone_name');
    // }

    // public function date_format()
    // {
    //     return $this->belongsTo(DateFormats::class, 'date_format')->select('id','title','format_key');
    // }

    public function user_profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }
}
