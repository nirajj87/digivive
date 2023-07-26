<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\State;

class CityResource extends JsonResource
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
            'id'        => $this->id,
            'title'     => $this->title,
            'state_id'  => $this->state_id,
            'state_name'=> $this->state ? $this->state->title : null,
            'country'     => $this->state->country->title,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'status'    => $this->status,

        ];

        
    }
}