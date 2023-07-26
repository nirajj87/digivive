<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientDeviceRestrictionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id'                    => $this->id,
            'number_of_device'      => $this->number_of_device,
            'os_id'                 => json_decode($this->os_id),
            'api_duration'          => $this->api_duration,
            'user_id'               => $this->user_id,
            'status'                => $this->status
        ];
    }
}
