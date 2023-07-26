<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountriesResource extends JsonResource
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
            'id'                => $this->id,
            'title'             => $this->title,
            'iso3'              => $this->iso3,
            'iso2'              => $this->iso2,
            'phone_code'        => $this->phone_code,
            'capital'           => $this->capital,
            'currency'          => $this->currency,
            'currency_name'     => $this->currency_name,
            'currency_symbol'   => $this->currency_symbol,
            'timezones'         => json_decode($this->timezones, true),
            'latitude'          => $this->latitude,
            'longitude'         => $this->longitude,
            'emoji'             => $this->emoji,
            'image'             => $this->image,
            'status'            => $this->status,
        ];
    }
}
