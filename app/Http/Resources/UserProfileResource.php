<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\City;
use App\Models\State;
use App\Models\Countries;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       $imageBasePath = env('APP_URL') . '/storage/' ;
        $manages = $this->manages;
        $manageslist = [];

        foreach ($manages as $manager) {
            $name = $manager->first_name;
            $name .= $manager->user_profile->designation ?? '';

            $manageslist[] = [
                'id' => $manager->id ?? '',
                'name' => $name ?? '',
                'email' => $manager->email ?? '',
                'profile_img' => isset($manager->profile_img) ? $imageBasePath . $manager->profile_img : '',
                'phone_number' => $manager->phone_number ?? '',
                'designation' => $manager->user_profile->designation ?? ''
            ];
        }

        $address = $this->user_profile ? json_decode($this->user_profile->address) : null;
        $billing = $this->user_profile ? json_decode($this->user_profile->billing) : null;
        $communication_channel = $this->user_profile ? json_decode($this->user_profile->communication_channel) : null;

        $country = '';
        $state = '';
        $city = '';

        if (isset($this->country_id) && ($this->country_id !='')) {
            $usercountry = Countries::select('id', 'title')->find($this->country_id) ?? '';
        }
        if (isset($address->country_id) && ($address->country_id != '')) {
            $country = Countries::select('id', 'title')->find($address->country_id) ?? '';
        }
        if(isset($address->state_id) && ($address->state_id !='')){
            $state = State::select('id', 'title', 'state_id')->find($address->state_id) ?? '';
        }
        if (isset($address->city_id) && ($address->city_id !='')) {
            $city = City::select('id', 'title', 'state_id')->find($address->city_id) ?? '';
        }

        $data = [
            'id' => $this->id,
            'first_name' => $this->first_name ?? "",
            'last_name' => $this->last_name ?? "",
            'email' => $this->email ?? "",
            'usercountry' => $usercountry ?: [
                'id' => '',
                'title' => '',
            ],
            'company_name' => $this->company_name ?? "",
            'timezone' => $this->timezone ?? "",
            'date_format' => $this->date_format ?? "",
            'role_id' => optional($this->role)->id ?? 0,
            'profile_img' => isset($this->profile_img) ? $imageBasePath . $this->profile_img : "",
            'status' => $this->status ?? 0,
            'dob' => $this->dob ?? "",
            'phone_number' => $this->phone_number ?? "",
            'is_two_factor_enable' => $this->is_two_factor_enable ? 1 : 0,
            'permission' => json_decode($this->permission) ?? [],
            'designation' => $this->user_profile ? $this->user_profile->designation ?? '' : '',
            'user_profile' => [
                'address' => [
                    'line1' => $address->line1 ?? '',
                    'line2' => $address->line2 ?? '',
                    'city' => $city ?: [
                        'id' => '',
                        'title' => '',
                        'state_id' => '',
                    ],
                    'state' => $state ?: [
                        'id' => '',
                        'title' => '',
                        'state_id' => '',
                    ],
                    'country' => $country ?: [
                        'id' => '',
                        'title' => '',
                    ],
                    'zip_code' => $address->zip_code ?? '',
                    'landmark' => $address->landmark ?? '',
                    'country_id' => $address->country_id ?? '',
                    'state_id' => $address->state_id ?? '',
                    'city_id' => $address->city_id ?? '',
                ],
                'billing' => [
                    'bank_name' => $billing->bank_name ?? '',
                    'benificiary_name' => $billing->benificiary_name ?? '',
                    'account_number' => $billing->account_number ?? '',
                    'ifsc_code' => $billing->ifsc_code ?? '',
                    'swift_code' => $billing->swift_code ?? '',
                    'address' => $billing->address ?? '',
                    'cheque' => $billing->cheque ?? "",
                    'gstin' => $billing->gstin ?? '',
                    'gstin_image' => isset($billing->gstin_image) ? $imageBasePath . $billing->gstin_image : '',
                    'cin' => $billing->cin ?? '',
                    'cin_image' => isset($billing->cin_image) ? $imageBasePath .$billing->cin_image : '',
                    'pan' => $billing->pan ?? '',
                    'pan_image' => isset($billing->pan_image) ? $imageBasePath .$billing->pan_image : ''
                ],
                'communication_channel' => [
                    'email' => $communication_channel->email ?? '',
                    'phone_number' => $communication_channel->phone_number ?? ''
                ],
                'managers' => $manageslist ?: [
                    'id' => '',
                    'name' => '',
                    'email' => '',
                    'profile_img' => '',
                    'phone_number' => '',
                    'designation' => ''
                ],
                'managers_id' => json_decode($this->user_profile->managers) ?? []
            ]
        ];

        return $data;
    }

    public static function ManagerDetailArray($users)
    {
        if ($users) {
            $newData = $users->map(function ($user) {
                $imageBasePath = env('APP_URL') . '/storage/';
                $name = $user->first_name;
                $designation = $user->user_profile ? $user->user_profile->designation : null;
                $name .= $designation ? " ({$designation})" : "";
                $data = [
                    'id' => $user->id,
                    'name' => $user->first_name,
                    'email' => $user->email,
                    'profile_img' => isset($user->profile_img) ? $imageBasePath . $user->profile_img : "",
                    'phone_number' => $user->phone_number ?? "",
                    'designation' => $designation ?? ""
                ];

                return $data;
            });
        } else {
            $newData = collect(); // Return an empty collection if $users is null
        }
        return $newData;
    }

    public static function CommunicationManagerDetailArray($users)
    {
       
        if ($users) {  
            $newData = $users->map(function ($user) {
                $imageBasePath = env('APP_URL') . '/storage/';
                $name = ($user->first_name && $user->last_name) ? $user->first_name . " " . $user->last_name : $user->first_name;
                $name .= $name . " ({$user->email})";
                $data = [
                    'id' => $user->id,
                    'name' => $name,
                    'email' => $user->email,
                    'profile_img' => isset($user->profile_img) ? $imageBasePath . $user->profile_img : "",
                    'phone_number' => $user->phone_number ?? "",
                    'designation' => $user->user_profile->designation ?? ""
                ];

                return $data;
            });
        }
        return $newData ?? [];
    }
}