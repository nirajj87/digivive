<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id'            => $this->id,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'email'         => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'password_last_updated' => $this->password_last_updated,
            'profile_img'   => $this->profile_img,
            'role_id'       => $this->role_id,
            'permission'    => json_decode($this->permission) ?? [],
            'country_id'    => $this->country_id,
            'timezone'      => $this->timezone,
            'date_format'   => $this->date_format,
            'dob'           => $this->dob,
            'phone_number'  => $this->phone_number,
            'status'        => $this->status,
            'is_two_factor_enable' => $this->is_two_factor_enable ? 1 : 0,
            'is_parent'     => $this->is_parent,
            'created_by'    => $this->created_by,
            'updated_by'    => $this->updated_by

        ];
    }
}