<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
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
            'title'         => $this->title,
            'state_code'    => $this->state_code,
            'state_id'      => $this->state_id,
            'country_id'    => $this->country_id,
            'country_name'  => $this->country->title,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'status'        => $this->status,
           
        ];
    }
}