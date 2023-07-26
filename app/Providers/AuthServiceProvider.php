<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Config;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Change verify email button url
        VerifyEmail::createUrlUsing(function ($notifiable) {
            $params = [
                "expires" => Carbon::now()
                    ->addMinutes(60)
                    ->getTimestamp(),
                "id" => $notifiable->getKey(),
                "hash" => sha1($notifiable->getEmailForVerification()),
            ];
    
            ksort($params);
            $url = \URL::route("verification.verify", $params, true);
    
            // get APP_KEY from config and create signature
            $key = config("app.key");
            $signature = hash_hmac("sha256", $url, $key);
    
            // generate url for yous SPA page to send it to user
            return env("WEB_URL") . "/auth/verify-email/" . $params["id"] . "/" . $params["hash"] . "?expires=" . $params["expires"] . "&signature=" . $signature;
        });

        //Change Reset password url
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return env('WEB_URL') . '/password/reset?token='.$token.'&email='.urlencode($user->email);
        });
    }
}
