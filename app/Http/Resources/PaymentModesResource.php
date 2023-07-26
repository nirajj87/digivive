<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentModesResource extends JsonResource
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
            'slug'      => $this->slug,
            'type'      => $this->type,
            'image'     => $this->image,
            'is_recurring' => $this->is_recurring,
            'additional_settings'  => $this->additional_settings,
            'status' => $this->status 
        ];
    }
}