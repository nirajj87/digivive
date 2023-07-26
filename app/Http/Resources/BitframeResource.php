<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BitframeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
{
    $presetsArray = explode('/', $this->presets);
    $presetsObject = [];

    foreach ($presetsArray as $key => $value) {
        $presetsObject[$key] = $value;
    }

    return [
        'id' => $this->id,
        'type' => $this->type,
        'presets' => $presetsObject,
        'is_downloadable' => $this->is_downloadable,
        'download_type' => $this->download_type,
        'status' => $this->status
    ];
}
}