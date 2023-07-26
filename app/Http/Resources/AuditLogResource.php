<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
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
            'id'                 => $this->id,
            'old_values'         => $this->old_values,
            'new_values'         => $this->new_values,
            'event'              => $this->event,
            'auditable_id'       => $this->auditable_id,
            'auditable_type'     => $this->auditable_type,
            'user_id'            => $this->user_id,
            'user_type'          => $this->user_type,
            'tags'               => $this->tags,
            'ip_address'         => $this->ip_address,
            'user_agent'         => $this->user_agent,
            'url'                => $this->url,
            'user_data'          => json_decode($this->user_data, true),
            'created_at'         => $this->created_at,
            
        ];
    }
}