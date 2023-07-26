<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageBasePath = env('APP_URL') . '/storage/';
        // return parent::toArray($request);
        return [
            'id'            => $this->id,
            'first_name'  => $this->first_name,
            'last_name'  => $this->last_name,
            'company_name'  => $this->company_name,
            'email'         => $this->email,
            'country_id'    => $this->country_id,
            'timezone'      => $this->timezone,
            'date_format'   => $this->date_format,
            'password_last_updated' => $this->password_last_updated,
            'profile_img'   => isset($this->profile_img) ? $imageBasePath.$this->profile_img : "",
            'role_id'       => $this->role,
            'status'        => $this->status,
            'permission'    => json_decode($this->permission) ?? [],
            'designation' => optional($this->user_profile->first())->designation ?? ''

        ];
    }
}
